<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Overrides\Okipa\Column as Column;
use App\Services\TableSettingsService;
use App\Tables\Filters\InputFilter;
use App\Tables\Filters\SelectFilter;
use App\Tables\Formatters\NameFormatter;


/**
 * Trait для определения ресуров для прав и удаления
 */
trait TablesColumnsTrait
{
    protected $filtees = [];

    protected function columns(): array
    {
        $columns = [];
        $table = $this->table();
        $model = $table->getModel();
        $serivce = new TableSettingsService($model);
        $serivce_columns = $serivce->getColumns();

        if (method_exists($model, 'setupFilters')) {
            $model->setupFilters($serivce_columns);
        }

        //колонки
        foreach ($serivce_columns as $value) {
            if (empty($value['is_checked'])) {
                continue;
            }

            $column = Column::make($value['column'])->title(__($value['label']));

            //поставим ссылку
            if (!empty($value['formatter'])) {
                $formatter = '\App\Tables\Formatters\\' . $value['formatter'];
                if (!class_exists($formatter)) {
                    throw new \Exception($formatter . ' Table Formatter not found');
                } else {
                    $column->format(new $formatter());
                }
            }
            if (!empty($value['is_order_default'])) {
                $column->sortable();
                $column->sortByDefault($value['order_default_dir']);
            } else {
                if (!empty($value['is_order'])) {
                    $column->sortable();
                }
            }
            if (!empty($value['width'])) {
                $column->width($value['width']);
            }
            //учавствует в глобальном поиске
            if (!empty($value['searchable'])) {
                $column->searchable($value['searchable']);
            }
            $r = new \ReflectionClass($column);
            foreach ($r->getProperties() as $prop) {
                $k = $prop->getName();
                unset($value[$k]);
            }
           
            foreach ($value as $k => $v) {
                $column->$k = $v;
            }
            $columns[] = $column;
        }

        return $columns;
    }
}
