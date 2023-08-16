<?php
namespace App\Tables\Filters;

use Okipa\LaravelTable\Abstracts\AbstractFilter;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Services\TableSettingsService;

class MultiInputFilter extends AbstractFilter
{
    public string $label;

    public string $attribute;

    public string $model;
    public static string $input_class;

    public $value = null;

    public function __construct($model, $label, $attribute)
    {
        $this->model = $model;
        $this->label = $label;
        $this->attribute = $attribute;

        //посмотрим значение в сессии
        $service = new TableSettingsService($this->model);
        $values = $service->getFilter();
        $identifer = $this->identifier();
        if (!empty($values[$identifer])) {
            $this->value = $values[$identifer];
        }
    }

    protected function identifier(): string
    {
        // фикс для точки
        $attribute = 'filter_value_' . str_replace('.', '-', $this->attribute);
        return $attribute;
    }

    protected function class (): array
    {
        return [
            // The CSS class that will be merged to the existent ones on the filter select.
            // As class are optional on filters, you may delete this method if you don't declare any specific class.
            // Note: you can use conditional class merging as specified here: https://laravel.com/docs/blade#conditionally-merge-classes
            ...parent::class ()
        ];
    }

    protected function attributes(): array
    {
        return [
            // The HTML attributes that will be merged to the existent ones on the filter select.
            // As attributes are optional on filters, you may delete this method if you do declare any specific attributes.
            ...parent::attributes()
        ];
    }

    protected function label(): string
    {
        return $this->label;
    }

    protected function multiple(): bool
    {
        return false;
    }

    protected function options(): array
    {
        return [];
    }

    public function filter(Builder $query, mixed $value): void
    {
        $columns = explode(',', $this->attribute);

        $query->where(function ($query) use ($value, $columns) {
            if (is_array($columns) && count($columns) > 0) {
                foreach ($columns as $k => $col) {
                    $query->orWhere($col, 'LIKE', '%' . Str::of($value)->trim()->lower() . '%');
                }
            }
        });
    }

    public function addClass($class = '')
    {
        self::$input_class = $class;
    }

    public function input_class(): string
    {
        return self::$input_class;
    }

    public function render(): View
    {
        return view('laravel-table::' . config('laravel-table.ui') . '.input-filter', [
            'filter' => $this,
            'class' => $this->class(),
            'attributes' => (new ComponentAttributeBag($this->attributes())),
            'label' => $this->label(),
        ]);
    }
}
