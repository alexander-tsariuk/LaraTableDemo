<?php
namespace App\ViewModels;


use Illuminate\Database\Eloquent\Builder;
use App\Models\Warehouse as BaseModel;

class Warehouse extends BaseModel
{

    protected $columns = [
        [
            'column' => 'name',
            // название поля из БД
            'label' => 'APP.TITLE',
            // название которое будет выводиться
            'formatter' => 'NameFormatter',
            // если поле надо выводить как-то по другому создаем в App\Tables\Formatters или можно тут через getNameAttribute
            'is_checked' => 1,
            // будет или нет выводиться в таблицу по умолчанию
            'is_order' => 1,
            // сортируемое
            'is_order_default' => 1,
            // это колонка будет сотрироваться по умолчанию
            'order_default_dir' => 'desc',
            // направление сортировки по умолчанию
            'is_show_filter' => 1,
            // показывать в фильтрах так же является сортировкой для фильтров
            'filter' => 'warehouses.name',
            // поле для фильтра WHERE
            'filter_type' => 'input',
            // тпи фильтра input - текстовое поле, date, select, boolean - select (Выбор, Да, Нет)
            'filter_options' => '',
            // массив значений для select
            'filter_model' => '',
            // модель для фильтра если тип select
            'filter_autocomlite' => '',
            // использовать autocomlite для Модели
            'searchable' => 'warehouses.name',
            // это поле будет учавствовать в глобальном поиске, ищется по указанной колонке
            'is_show_filter' => 1
        ],
        [
            'column' => 'id',
            'label' => 'APP.CODE',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 1,
            'filter' => 'warehouses.id',
            'filter_type' => 'input'
        ],
        [
            'column' => 'is_main',
            'label' => 'APP.IS_MAIN',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 1,
            'filter' => 'warehouses.is_main',
            'filter_type' => 'input'
        ],
    ];


    public function setupFilters(&$columns)
    {
        //
    }


    public function scopeDataTable(Builder $builder) {
        $model = $builder->getModel();
        $table = $model->getTable();
        $query = $builder->getQuery();

        $builder->groupBy($table . '.id');
    }


}
