<?php
namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Okipa\LaravelTable\Abstracts\AbstractFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\TableSettingsService;

class SelectFilter extends AbstractFilter
{

    public string $model;

    public string $label;

    public string $attribute;

    public array $options;

    public bool $multiple;

    public $value = null;

    public $choose = false;
    public $attrs = [];

    public function __construct($model, $label, $attribute, $options = null, $multiple = true, $attrs = [])
    {
        $this->model = $model;
        $this->label = $label;
        $this->attribute = $attribute;
        $this->options = $options;
        $this->multiple = $multiple;
        $this->attrs = $attrs;

        //посмотрим значение в сессии
        $service = new TableSettingsService($this->model);
        $values = $service->getFilter();
        $identifer = $this->identifier();
        if (!empty($values[$identifer])) {
            $this->value = $values[$identifer];
        }

        if (!$this->multiple) {
            $this->choose = 1;
            if (!isset($this->value)) {
                $this->value = -1;
            }
        }
    }

    protected function identifier(): string
    {
        // фикс для точки
        $attribute = 'filter_value_' . str_replace('.', '-', $this->attribute);
        return $attribute;
    }

    protected function label(): string
    {
        return $this->label;
    }

    protected function multiple(): bool
    {
        return $this->multiple;
    }

    protected function options(): array
    {
        return $this->options;
    }

    public function filter(Builder $query, mixed $selected): void
    {
        //фикс для сотрудников у контрагентов
        if ($this->identifier == 'filter_value_managers-user_id') {
            $sql = '(SELECT 1
                    FROM clients_managers
                    WHERE client_id = clients.id AND user_id IN (' . implode(',', Arr::wrap($selected)) . ') LIMIT 1) IS NOT NULL';
            $query->whereRaw($sql);
        } else {
            $query->whereIn($this->attribute, Arr::wrap($selected));
        }
    }
}
