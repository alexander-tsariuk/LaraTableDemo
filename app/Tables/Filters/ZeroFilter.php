<?php
namespace App\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Okipa\LaravelTable\Abstracts\AbstractFilter;
use Illuminate\Support\Facades\Session;
use App\Services\TableSettingsService;

class ZeroFilter extends AbstractFilter
{
    
    public string $model;
    
    public string $label;
    
    public string $attribute;
    
    public array $options;
    
    public bool $multiple;
    
    public $value = null;
    
    public $choose = false;
    
    public function __construct($model, $label, $attribute, $options = null, $multiple = true)
    {
        $this->model = $model;
        $this->label = $label;
        $this->attribute = $attribute;
        $this->options = $options;
        $this->multiple = $multiple;
        
        
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
        if (!$selected) {
            $query->whereNull($this->attribute);
        } else {
            $query->where($this->attribute, '>', 0);
        }

       
    }
}
