<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\Client\ClientService;
use Illuminate\Support\Facades\Auth;

/**
 * Сервис для настроке различных таблиц
 *
 * @author t4
 *        
 */
class TableSettingsService
{

    /**
     * Модель
     *
     * @var string
     */
    private $model;

    private $session_key;

    /**
     * Поле в таблице БД куда будут сохраняться настройки таблицы
     *
     * @var string
     */
    private $client_field = 'table_view_settings';

    /**
     * Ключ для массива сессии, куда будут ложится временные данные
     *
     * @var string
     */
    private $session_prefix = 'tables';

    /**
     * Данные из сессии для модели
     *
     * @var array
     */
    private $session_data = [];

    /**
     * Модели, которые разделены по типу, параметр type в урле
     *
     * @var array
     */
    protected $type_models = [
        'App\ViewModels\Crm\Document',
        'App\ViewModels\Crm\Finance'
    ];

    private static $columns;

    /**
     *
     * @param string|object $model
     */
    public function __construct($model)
    {
       
        if (! is_object($model)) {
            $this->model = new $model();
        } else {
            $this->model = $model;
        }
        $this->session_key = get_class($this->model);
        if (in_array($this->session_key, $this->type_models)) {
            if (! empty($this->model->type_id)) {
                $this->session_key .= '_' . $this->model->type_id;
                $input = [
                    'table_params' => [
                        'type' => $this->model->type_id
                    ]
                ];
                request()->request->add($input);
            }
        }
        if (! empty(Session::get($this->session_prefix))) {
            $this->session_data = Session::get($this->session_prefix);
        }
    }

    /**
     * Показ настроек таблицы
     */
    public function show()
    {
        $all_columns = $this->getColumns();
        $client = Auth::user();
        $field = $this->client_field;
        if (! empty($client->$field[$this->session_key]['columns'])) {
            $columns = $client->$field[$this->session_key]['columns'];
            //добавим колонки если какие-то появились
            foreach ($all_columns as $column) {
                $add = true;
                foreach ($columns as $value) {
                    if ($value['column'] == $column['column']) {
                        $add = false;
                        break;
                    }
                }
                if ($add) {
                    $columns[] = $column;
                }
            }
        } else {
            $columns = $all_columns;
            //dd($columns);
        }

        $type = ! empty($this->model->type_id) ? $this->model->type_id : false;
        $action = route($this->model->getRoute() . '.tablesettings');
        return view('common.table-settings', compact('columns', 'action', 'type'))->render();
    }

    /**
     * Сохранение настроек для таблиц
     *
     * @param Request $data
     * @return boolean
     */
    public function saveColumns(Request $data)
    {
        $client = Auth::user();

        $field = $this->client_field;
        if (empty($client->$field) || ! is_array($client->$field)) {
            $table_view_settings = [];
        } else {
            $table_view_settings = $client->$field;
        }

        $default = $data->post('is_order_default');
        $default_dir = $data->post('order_default_dir');

        $columns = $data->get('columns');
        if (empty($columns)) {
            Session::flash('error', __('APP.OOPS_ERROR'));
            return false;
        }

        // если устанавливаем по умолчанию
        if (! empty($data['reset_table'])) {
            unset($table_view_settings[$this->session_key]);
            $message = __('APP.SET_DEFAULT_VALUES_SET');
        } else {
            $all_columns = $this->model->getColumns();
            $all_columns_by_column = [];
            foreach ($all_columns as $value) {
                unset($value['is_order_default']);
                unset($value['order_default_dir']);
                unset($value['is_checked']);
                unset($value['is_show_filter']);
                $all_columns_by_column[$value['column']] = $value;
            }
            $client_columns = [];
            foreach ($columns as $key => $value) {
                $column = $all_columns_by_column[$value['column']];
                // установим сортировку по умолчанию
                if ($key == $default) {
                    $columns[$key]['is_order_default'] = 1;
                    $columns[$key]['order_default_dir'] = $default_dir;
                }
                $client_columns[] = array_merge($column, $columns[$key]);
            }
            $table_view_settings[$this->session_key]['columns'] = $client_columns;
            $message = __('APP.SUCCESSFULLY_UPDATED');
        }
        // сохраним в БД
        $client->where('id', $client->id)->update([
            $field => json_encode($table_view_settings, JSON_UNESCAPED_UNICODE)
        ]);

        // очистим данные в сессии
        if (! empty($this->session_data[$this->session_key])) {
            unset($this->session_data[$this->session_key]);
            Session::put($this->session_prefix, $this->session_data);
        }
        $messageService = new MessageService();
        $messageService->setMessage($message);
        return true;
    }

    /**
     * Получение колонок
     */
    public function getColumns()
    {
        if (! empty(self::$columns)) {
            return self::$columns;
        }

        $client = Auth::user();
        $field = $this->client_field;

        $all_columns = $this->model->getColumns();
        // для модалок будем брать только из модели
        if (request('modal')) {
            $columns = $all_columns;
        } else {
            if (! empty($client->$field[$this->session_key]['columns'])) {
                $columns = $client->$field[$this->session_key]['columns'];
              
            } else {
                // TODO как все будет готово можно будет включить
                if (! empty($this->session_data[$this->session_key]['columns']) && false) {
                    $columns = $this->session_data[$this->session_key]['columns'];
                } else {
                    $columns = $all_columns;
                }
            }

        }

        // проверим возможно эта колонка была удалена или изменена
        if ($columns != $all_columns) {
            foreach ($columns as $key => $value) {
                $remove = true;
                foreach ($all_columns as $k => $column) {
                    if ($column['column'] == $value['column']) {
                        $remove = false;
                        unset($all_columns[$k]);
                        break;
                    }
                }
                if ($remove) {
                    unset($columns[$key]);
                }
            }
        }
        ksort($columns);

        self::$columns = $columns;
        $this->session_data[$this->session_key]['columns'] = $columns;
        // положим в сессию что бы потом не выбирать
        Session::put($this->session_prefix, $this->session_data);
        return $columns;
    }

    /**
     * Сохранение значений для фильтров
     *
     * @param array $data
     */
    public function setFilter($data)
    {

        // для модальных окон не будем сохранять
        if (! request('modal')) {
            $this->session_data[$this->session_key]['filters'] = $data;
            Session::put($this->session_prefix, $this->session_data);
        }
    }

    /**
     * Получение значений для фильтров из сессии
     */
    public function getFilter()
    {
        if (! empty($this->session_data[$this->session_key]['filters']) && ! request('modal')) {
            return $this->session_data[$this->session_key]['filters'];
        } else {
            return [];
        }
    }

    /**
     * Установка сортировки для сессии
     *
     * @param array $data
     */
    public function setOrder($data)
    {
        $this->session_data[$this->session_key]['order'] = $data;
        Session::put($this->session_prefix, $this->session_data);
    }

    /**
     * Получение значений для сортировки из сессии
     */
    public function getOrder()
    {
        if (! empty($this->session_data[$this->session_key]['order'])) {
            $order = $this->session_data[$this->session_key]['order'];
            $result = false;
            if ($order) {
                // проверим есть ли такая колонка
                $columns = $this->getColumns();
                foreach ($columns as $column) {
                    if ($column['column'] == $order['column']) {
                        $result = $order;
                        break;
                    }
                }
            }
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Установка лимита для сессии
     *
     * @param array $data
     */
    public function setLimit($data)
    {
        $this->session_data[$this->session_key]['limit'] = $data;
        Session::put($this->session_prefix, $this->session_data);
    }

    /**
     * Получение значений для лимита из сессии
     */
    public function getLimit()
    {
        if (! empty($this->session_data[$this->session_key]['limit'])) {
            return $this->session_data[$this->session_key]['limit'];
        } else {
            return false;
        }
    }

    /**
     * Установка поиска для сессии
     *
     * @param string $data
     */
    public function setSearch($data)
    {
        $this->session_data[$this->session_key]['search'] = $data;
        Session::put($this->session_prefix, $this->session_data);
    }

    /**
     * Получение значения поиска из сессии
     */
    public function getSearch()
    {
        if (! empty($this->session_data[$this->session_key]['search']) && ! request('modal')) {
            return $this->session_data[$this->session_key]['search'];
        } else {
            return '';
        }
    }
}
