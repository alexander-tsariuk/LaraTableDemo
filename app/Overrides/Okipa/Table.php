<?php

namespace App\Overrides\Okipa;


use App\Tables\Filters\CheckboxFilter;
use App\Tables\Filters\DateFilter;
use App\Tables\Filters\DateRangeFilter;
use App\Tables\Filters\DateTimeFilter;
use App\Tables\Filters\MultiInputFilter;
use App\Tables\Filters\NumberFilter;
use App\Tables\Filters\SummRangeFilter;
use App\Tables\Filters\TextareaFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Okipa\LaravelTable\Abstracts\AbstractFilter;
use Okipa\LaravelTable\Abstracts\AbstractHeadAction;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;
use Okipa\LaravelTable\Exceptions\NoColumnsDeclared;
use App\Overrides\Okipa\Column as Column;
use App\Services\TableSettingsService;
use App\Tables\Filters\InputFilter;
use App\Tables\Filters\SelectFilter;
use App\Tables\Filters\ZeroFilter;

/** @SuppressWarnings(PHPMD.ExcessiveClassComplexity) */
class Table
{
    protected Model $model;

    protected array $eventsToEmitOnLoad = [];

    protected Column|null $orderColumn = null;

    protected bool $numberOfRowsPerPageChoiceEnabled;

    protected array $numberOfRowsPerPageOptions;

    public array $filters = [];

    protected AbstractHeadAction|null $headAction = null;

    protected Closure|null $rowActionsClosure = null;

    protected Closure|null $bulkActionsClosure = null;

    protected Closure|null $queryClosure = null;

    protected Closure|null $rowClassesClosure = null;

    protected Collection $columns;

    protected Collection $results;

    protected LengthAwarePaginator $rows;

    protected static $setup_filters;

    public $title;

    public $underHeadLine;

    public function __construct()
    {
        $this->numberOfRowsPerPageChoiceEnabled = config('laravel-table.enable_number_of_rows_per_page_choice');
        $this->numberOfRowsPerPageOptions = config('laravel-table.number_of_rows_per_page_default_options');
        $this->columns = collect();
        $this->results = collect();
    }

    public function model(string $modelClass): self
    {
        $this->model = app($modelClass);

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function emitEventsOnLoad(array $eventsToEmitOnLoad): self
    {
        $this->eventsToEmitOnLoad = $eventsToEmitOnLoad;

        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\InvalidColumnSortDirection */
    public function reorderable(string $attribute, string $title = null, string $sortDirByDefault = 'asc'): self
    {
        $orderColumn = Column::make($attribute)->sortable()->sortByDefault($sortDirByDefault);
        if ($title) {
            $orderColumn->title($title);
        }
        $this->orderColumn = $orderColumn;

        return $this;
    }

    public static function make(): self
    {
        return new self();
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function prependReorderColumn(): void
    {
        $orderColumn = $this->getOrderColumn();
        if ($orderColumn) {
            $this->getColumns()->prepend($orderColumn);
        }
    }

    public function getOrderColumn(): Column|null
    {
        return $this->orderColumn;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getColumns(): Collection
    {
        if ($this->columns->isEmpty()) {
            throw new NoColumnsDeclared($this->model);
        }

        return $this->columns;
    }

    public function getReorderConfig(string|null $sortDir): array
    {
        if (!$this->getOrderColumn()) {
            return [];
        }
        $query = $this->model->DataTable();
        // Query
        if ($this->queryClosure) {
            $query->where(fn($subQueryQuery) => ($this->queryClosure)($query));
        }
        // Sort
        $query->orderBy($this->getOrderColumn()->getAttribute(), $sortDir);

        return [
            'modelClass' => $this->model::class,
            'reorderAttribute' => $this->getOrderColumn()->getAttribute(),
            'sortDir' => $sortDir,
            'beforeReorderAllModelKeysWithPosition' => $query
                ->get()
                ->map(fn(Model $model) => [
                    'modelKey' => (string) $model->getKey(),
                    'position' => $model->getAttribute($this->getOrderColumn()->getAttribute()),
                ])
                ->toArray(),
        ];
    }

    public function query(Closure $queryClosure): self
    {
        $this->queryClosure = $queryClosure;

        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function prepareQuery(
        array $filterClosures,
        string|null $searchBy,
        string|Closure|null $sortBy,
        string|null $sortDir
    ): Builder {

        $query = $this->model->DataTable();

        // Query
        if ($this->queryClosure) {
            $query->where(fn(Builder $subQueryQuery) => ($this->queryClosure)($query));
        }

        // Filters
        if ($filterClosures) {
            //пропуск фильтров для доп. полей, они делаются в FilterTrait;
            foreach ($filterClosures as $k => $v) {
                if (str_contains($k, 'ffield'))
                    unset($filterClosures[$k]);
            }

            $query->where(function (Builder $subFiltersQuery) use ($filterClosures) {
                foreach ($filterClosures as $filterClosure) {
                    $filterClosure($subFiltersQuery);
                }
            });
        }
        // Search
        if ($searchBy) {
            $query->where(function (Builder $subSearchQuery) use ($searchBy) {
                $this->getSearchableColumns()
                    ->each(function (Column $searchableColumn) use ($subSearchQuery, $searchBy) {

                        $searchableClosure = $searchableColumn->getSearchableClosure();
                        $searchableClosure
                            ? $subSearchQuery->orWhere(
                                fn(Builder $orWhereQuery) => ($searchableClosure)(
                                    $orWhereQuery,
                                    $searchBy
                                )
                            )
                            : $subSearchQuery->orWhereRaw(
                                $this->getSearchSqlStatement($searchableColumn->isSearchable()),
                                ['%' . Str::of($searchBy)->trim()->lower() . '%']
                            );

                    });

            });

        }
        // Sort
        if ($sortBy && $sortDir) {
            $sortBy instanceof Closure
                ? $sortBy($query, $sortDir)
                : $query->orderBy($sortBy, $sortDir);
        }

        return $query;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    protected function getSearchableColumns(): Collection
    {
        return $this->getColumns()->filter(fn(Column $column) => $column->isSearchable());
    }

    protected function getSearchSqlStatement(string $attribute): string
    {
        $connection = config('database.default');
        $driver = config('database.connections.' . $connection . '.driver');

        return $this->getSqlLowerFunction($driver, $attribute) . ' '
            . $this->getSqlCaseInsensitiveSearchingLikeOperator($driver) . ' ?';
    }

    protected function getSqlLowerFunction(string $driver, string $attribute): string
    {
        return $driver === 'pgsql' ? 'LOWER(CAST(' . $attribute . ' AS TEXT))' : 'LOWER(' . $attribute . ')';
    }

    protected function getSqlCaseInsensitiveSearchingLikeOperator(string $driver): string
    {
        return $driver === 'pgsql' ? 'ILIKE' : 'LIKE';
    }

    public function getRows(): LengthAwarePaginator
    {
        return $this->rows;
    }

    public function triggerEventsEmissionOnLoad($table): void
    {
        foreach ($this->eventsToEmitOnLoad as $event => $params) {
            $eventName = is_string($event) ? $event : $params;
            $eventParams = is_array($params) ? $params : [];
            $table->emit($eventName, $eventParams);
        }
    }

    public function enableNumberOfRowsPerPageChoice(bool $numberOfRowsPerPageChoiceEnabled): self
    {
        $this->numberOfRowsPerPageChoiceEnabled = $numberOfRowsPerPageChoiceEnabled;

        return $this;
    }

    public function isNumberOfRowsPerPageChoiceEnabled(): bool
    {
        return $this->numberOfRowsPerPageChoiceEnabled;
    }

    public function numberOfRowsPerPageOptions(array $numberOfRowsPerPageOptions): self
    {
        $this->numberOfRowsPerPageOptions = $numberOfRowsPerPageOptions;

        return $this;
    }

    public function getNumberOfRowsPerPageOptions(): array
    {
        return $this->numberOfRowsPerPageOptions;
    }

    public function filters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    public function headAction(AbstractHeadAction $headAction): self
    {
        $this->headAction = $headAction;

        return $this;
    }

    public function rowActions(Closure $rowActionsClosure): self
    {
        $this->rowActionsClosure = $rowActionsClosure;

        return $this;
    }

    public function bulkActions(Closure $bulkActionsClosure): self
    {
        $this->bulkActionsClosure = $bulkActionsClosure;

        return $this;
    }

    public function rowClass(Closure $rowClassesClosure): self
    {
        $this->rowClassesClosure = $rowClassesClosure;

        return $this;
    }

    public function columns(array $columns): void
    {
        $this->columns = collect($columns);
    }

    public function results(array $results): void
    {
        $this->results = collect($results);
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getColumnSortedByDefault(): Column|null
    {
        $sortableColumns = $this->getColumns()
            ->filter(fn(Column $column) => $column->isSortable($this->getOrderColumn()));
        if ($sortableColumns->isEmpty()) {
            return null;
        }
        $columnSortedByDefault = $sortableColumns->filter(fn(Column $column) => $column->isSortedByDefault())->first();
        if (!$columnSortedByDefault) {
            return $sortableColumns->first();
        }

        return $columnSortedByDefault;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getColumn(string $attribute): Column
    {
        //dd($this->getColumns());
        return $this->getColumns()->filter(fn(Column $column) => $column->getAttribute() === $attribute)->first();
    }

    public function paginateRows(Builder $query, int $numberOfRowsPerPage): void
    {
        $this->rows = $query->paginate($numberOfRowsPerPage);
        $this->rows->transform(function (Model $model) {
            $model->laravel_table_unique_identifier = Str::uuid()->getInteger()->toString();

            return $model;
        });
    }

    public function computeResults(Collection $displayedRows): void
    {
        $this->results = $this->results->map(
            fn(Result $result) => $result->compute(
                $this->model->DataTable()->toBase(),
                $displayedRows,
            )
        );
    }

    public function generateFiltersArray(): array
    {
        return collect($this->filters)->map(function (AbstractFilter $filter) {
            $filter->setup($this->model->getKeyName());

            return json_decode(
                json_encode(
                    $filter,
                    JSON_THROW_ON_ERROR
                ),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        })->toArray();
    }

    public function getFilterClosures(array $filtersArray, array $selectedFilters): array
    {
        $filterClosures = [];
        foreach ($selectedFilters as $identifier => $value) {
            if ($value === '' || $value === []) {
                continue;
            }
            $filterArray = AbstractFilter::retrieve($filtersArray, $identifier);
            $filterInstance = AbstractFilter::make($filterArray);
            $filterClosures[$identifier] = static fn(Builder $query) => $filterInstance->filter($query, $value);
        }

        return $filterClosures;
    }

    public function getHeadActionArray(): array
    {
        if (!$this->headAction) {
            return [];
        }
        $this->headAction->setup();
        if (!$this->headAction->isAllowed()) {
            return [];
        }

        return (array) $this->headAction;
    }

    /** @throws \JsonException */
    public function getRowClass(): array
    {
        $tableRowClass = [];
        if (!$this->rowClassesClosure) {
            return $tableRowClass;
        }
        foreach ($this->rows->getCollection() as $model) {
            $tableRowClass[$model->laravel_table_unique_identifier] = ($this->rowClassesClosure)($model);
        }

        return json_decode(
            json_encode(
                $tableRowClass,
                JSON_THROW_ON_ERROR
            ),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /** @throws \JsonException */
    public function generateBulkActionsArray(array $selectedModelKeys): array
    {
        $tableBulkActionsArray = [];
        $tableRawBulkActionsArray = [];
        if (!$this->bulkActionsClosure) {
            return $tableBulkActionsArray;
        }
        $bulkActionsModelKeys = [];
        foreach ($this->rows as $index => $model) {
            $modelBulkActions = collect(($this->bulkActionsClosure)($model));
            foreach ($modelBulkActions as $modelBulkAction) {
                $modelBulkAction->setup($model);
                if (!$index) {
                    $tableRawBulkActionsArray[] = json_decode(
                        json_encode(
                            $modelBulkAction,
                            JSON_THROW_ON_ERROR
                        ),
                        true,
                        512,
                        JSON_THROW_ON_ERROR
                    );
                }
                if (!in_array((string) $model->getKey(), $selectedModelKeys, true)) {
                    continue;
                }
                $modelBulkAction->isAllowed()
                    ? $bulkActionsModelKeys[$modelBulkAction->identifier]['allowed'][] = $model->getKey()
                    : $bulkActionsModelKeys[$modelBulkAction->identifier]['disallowed'][] = $model->getKey();
            }
        }
        foreach ($tableRawBulkActionsArray as $tableBulkActionArray) {
            $identifier = $tableBulkActionArray['identifier'];
            $tableBulkActionArray['allowedModelKeys'] = $bulkActionsModelKeys[$identifier]['allowed'] ?? [];
            $tableBulkActionArray['disallowedModelKeys'] = $bulkActionsModelKeys[$identifier]['disallowed'] ?? [];
            $tableBulkActionsArray[] = $tableBulkActionArray;
        }

        return $tableBulkActionsArray;
    }

    public function generateRowActionsArray(): array
    {
        $tableRowActionsArray = [];
        if (!$this->rowActionsClosure) {
            return $tableRowActionsArray;
        }
        foreach ($this->rows->getCollection() as $model) {
            $rowActions = collect(($this->rowActionsClosure)($model))
                ->filter(fn(AbstractRowAction $rowAction) => $rowAction->isAllowed());
            $rowActionsArray = $rowActions->map(static function (AbstractRowAction $rowAction) use ($model) {
                $rowAction->setup($model);

                return json_decode(
                    json_encode(
                        $rowAction,
                        JSON_THROW_ON_ERROR
                    ),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            })->toArray();
            $tableRowActionsArray = [...$tableRowActionsArray, ...$rowActionsArray];
        }

        return $tableRowActionsArray;
    }

    /**
     * @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared
     * @throws \JsonException
     */
    public function generateColumnActionsArray(): array
    {
        $tableColumnActionsArray = [];
        foreach ($this->rows->getCollection() as $model) {
            $columnActions = $this->getColumns()
                ->mapWithKeys(fn(Column $column) => [
                    $column->getAttribute() => $column->getAction()
                    ? $column->getAction()($model)
                    : null,
                ])
                ->filter();
            foreach ($columnActions as $attribute => $columnAction) {
                $columnAction->setup($model, $attribute);
                $tableColumnActionsArray[] = json_decode(
                    json_encode(
                        $columnAction,
                        JSON_THROW_ON_ERROR
                    ),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
            }
        }

        return $tableColumnActionsArray;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\NoColumnsDeclared */
    public function getSearchableLabels(): string
    {
        return $this->getSearchableColumns()
            ->map(fn(Column $searchableColumn) => ['title' => $searchableColumn->getTitle()])
            ->implode('title', ', ');
    }

    public function getResults(): Collection
    {
        return $this->results;
    }

    public function getNavigationStatus(): string
    {
        return __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
            'start' => $this->rows->isNotEmpty()
            ? ($this->rows->perPage() * ($this->rows->currentPage() - 1)) + 1
            : 0,
            'stop' => $this->rows->count() + (($this->rows->currentPage() - 1) * $this->rows->perPage()),
            'total' => $this->rows->total(),
        ]);
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function addFilters()
    {
        //dump(request('type'));
        $class = get_class($this->model);
        foreach ($this->columns as $value) {
            if (empty($value->is_show_filter) || empty($value->filter_type)) {
                continue;
            }
            $filters[] = (array) $value;
        }
        if (empty($filters)) {
            return;
        }

        //отсоритруем
        $filters = collect($filters)->sortBy('show_filter');
        foreach ($filters as $value) {
            switch ($value['filter_type']) {
                case 'daterange':
                    $filter = new DateRangeFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'summrange':
                    $filter = new SummRangeFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'input':
                    $filter = new InputFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'date':
                    $filter = new DateFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'datetime':
                    $filter = new DateTimeFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'number':
                    $filter = new NumberFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'textarea':
                    $filter = new TextareaFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'checkbox':
                    $filter = new CheckboxFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'multiinput':
                    $filter = new MultiInputFilter($class, __($value['label']), $value['filter']);
                    $this->filters[] = $filter;
                    break;
                case 'select':
                    $options = [];
                    $attrs = [];
                    if (!empty($value['filter_model'])) {
                        if (!empty($value['filter_autocomplete'])) {
                            $attrs['autocomplete'] = 1;
                            $attrs['model'] = $value['filter_model'];
                        } else {
                            $options = $value['filter_model']::pluck('name', 'id')->toArray();
                        }
                    }
                    if (!empty($value['filter_options'])) {
                        $options = $value['filter_options'];
                    }
                    $this->filters[] = new SelectFilter($class, __($value['label']), $value['filter'], $options, 1, $attrs);
                    break;
                case 'boolean':
                    $options = ['0' => __('APP._NOT'), '1' => __('APP._YES')];
                    $this->filters[] = new SelectFilter($class, __($value['label']), $value['filter'], $options, false);
                    break;
                case 'zero':
                    $options = ['0' => __('APP._NOT'), '1' => __('APP._YES')];
                    $this->filters[] = new ZeroFilter($class, __($value['label']), $value['filter'], $options, false);
                    break;
                default:
                    ;
                    break;
            }
        }
        self::$setup_filters = $this->filters;
        return $this;
    }


    public function underHeadLine($value)
    {
        $this->underHeadLine = $value;
        return $this;
    }
}
