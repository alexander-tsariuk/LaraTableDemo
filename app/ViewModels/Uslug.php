<?php
namespace App\ViewModels;


use Illuminate\Database\Eloquent\Builder;
use App\Models\Uslug as BaseModel;

class Uslug extends BaseModel
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
            'filter' => 'uslugs.name',
            // поле для фильтра WHERE
            'filter_type' => 'input',
            // тпи фильтра input - текстовое поле, date, select, boolean - select (Выбор, Да, Нет)
            'filter_options' => '',
            // массив значений для select
            'filter_model' => '',
            // модель для фильтра если тип select
            'filter_autocomlite' => '',
            // использовать autocomlite для Модели
            'searchable' => 'uslugs.name',
            // это поле будет учавствовать в глобальном поиске, ищется по указанной колонке
            'is_show_filter' => 1
        ],
        [
            'column' => 'vendor_code',
            'label' => 'APP.VENDOR_CODE',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 1,
            'filter' => 'uslugs.vendor_code',
            'filter_type' => 'input'
        ],
        [
            'column' => 'unit',
            'label' => 'APP.UNIT',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 1,
            'filter' => 'unit_id',
            'filter_model' => 'App\Models\Unit',
            'filter_type' => 'select',
            'formatter' => 'LangFormatter'
        ],
        [
            'column' => 'buy_price',
            'label' => 'APP.COST_ZAK',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 0,
            'filter' => 'buy_price',
            'filter_type' => 'summrange'
        ],
        [
            'column' => 'sale_price',
            'label' => 'APP.PROD',
            'is_checked' => 1,
            'width' => '100px',
            'is_order' => 1,
            'is_show_filter' => 0,
            'filter' => 'sale_price',
            'filter_type' => 'summrange'
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
        $query->leftJoin('units AS u', 'u.id', '=', $table.'.unit_id');
        
        if (is_null($query->columns)) {
            $query->select([
                $table . '.*',
                'u.name AS unit',
            ]);
        }
        
        $builder->groupBy($table . '.id');
    }


}
