<?php

namespace App\View\Components\Forms;

use App\Models\BaseModel;
use Illuminate\View\Component;
use Illuminate\View\View;
use stdClass;

class Select extends Component
{
    public $label;
    public $show_field;
    public $value = [];
    public $options = [];
    public $model;
    public $multiple;
    public $hide_search;
    public $choose;
    public $autocomplete;
    public $add;
    public $list;
    public $icons;
    protected $route;
    public $where;
    public $modeldata;
    public $listtitle;
    public static $models;




    public function __construct($label = null, $show_field = null, $options = [], $value = [], $multiple = false, $hide_search = false, $choose = false, $model = false, $autocomplete = false, $add = false, $list = false, $where = false, $icon = [], $icons = [], $modeldata = [], $listtitle = false)
    {
        $this->label = $label;
        $this->show_field = $show_field;
        $this->multiple = $multiple;
        $this->hide_search = $hide_search;
        $this->choose = $choose;
        $this->autocomplete = $autocomplete;
        $this->where = $where;
        $this->add = $add;
        $this->list = $list;
        $this->modeldata = $modeldata;
        $this->listtitle = $listtitle;
        $this->icons = $icons;
        //если одна иконка
        if (!empty($icon)) {
            $this->icons[] = $icon;
        }

        if (!is_array($value)) {
            $this->value = [
                $value
            ];

        } else {
            $this->value = $value;
        }
        if ($model) {

            if (strpos($model, '\\') !== 1) {
                $this->model = '\\' . $model;
            }
            if (!class_exists($model)) {
                throw new \Exception($model - ' Model for Select not found');
            }
            $model = new $this->model();
            $this->route = $model->getRoute();

            if (empty($where)) {
                $model_name = stripslashes(get_class($model));
            } else {
                $model_name = stripslashes(get_class($model).json_encode($where));
            }

            if (!$this->autocomplete) {
                if (empty(self::$models[$model_name])) {
                    if (!$autocomplete) {
                        if ($where) {

                            $model = $model->where($where);
                        }
                        $options = $model->get()->toArray();
                    } else {
                        if (!$this->multiple) {
                            $this->choose = 1;
                        }
                        //если установлено значение его надо подставить
                        if ($this->value) {
                            $options = $model->whereIn($model->getTable() . '.id', $this->value)->get()->toArray();
                        }
                    }
                    self::$models[$model_name] = [];
                    self::$models[$model_name]['options'] = $options;
                    self::$models[$model_name]['route'] = $this->route;
                } else {
                    $options = self::$models[$model_name]['options'];
                }
            } else {
                if ($model && $value && empty($options)) {
                    //подставим значение из модели
                    $value = !is_array($value) ? [$value] : $value;
                    $options = $model::whereIn('id', $value)->get()->toArray();
                }
            }
        }

        if (!empty($options)) {
            foreach ($options as $key => $option) {
                if (is_string($option) || is_int($option)) {
                    $v = new \stdClass();
                    $v->value = $key;
                    $v->name = $option;
                    $options[$key] = $v;
                    continue;
                }
                if (is_array($option)) {
                    $options[$key] = (object) $option;
                }
                if (!empty($options[$key]->id) && !isset($options[$key]->value)) {
                    $options[$key]->value = $options[$key]->id;
                }
            }
        } else {
            $options = [];
        }
        $this->options = $options;
        if ($this->choose) {
            // dd($this->options);;
            $empty = new \StdClass;
            $empty->value = '';
            $empty->name = __('APP.CHOOSE');
            if (is_a($this->options, 'Illuminate\Database\Eloquent\Collection')) {
                $this->options->prepend($empty);
            } else {
                array_unshift($this->options, $empty);
            }
        }
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.forms.select');
    }

    /**
     * Set the extra attributes that the component should make available.
     *
     * @param array $attributes
     * @return $this
     */
    public function withAttributes(array $attributes)
    {
        // dd($attributes);
        // поставим максимум 5 элементов
        if (empty($attributes['data-size'])) {
            $attributes['data-size'] = '5';
        }
        $attributes['data-none-selected-text'] = __('APP.NOT_CHOSEN');
        if (!empty($this->multiple)) {
            $attributes['multiple'] = 'multiple';
            $attributes['data-actions-box'] = 'true';
            $attributes['data-count-selected-text'] = __('APP.SELECT_COUNT') . ': {0}';
            $attributes['data-select-all-text'] = __('APP.SELECT_ALL');
            $attributes['data-deselect-all-text'] = __('APP.DESELECT_ALL');
            if (empty($attributes['data-selected-text-format'])) {
                $attributes['data-selected-text-format'] = 'count > 1';
            }
        }



        //автокомпли
        if (!empty($this->autocomplete)) {
            if (empty($this->model)) {
                throw new \Exception($this->model . ' Model for autocomplete not set');
            }
            $model = new $this->model();
            $route = $model->getRoute();

            $attributes['data-url'] = route($route . '.autocomplete', $this->modeldata);
            $attributes['data-live-search'] = 'true';
        }

        // поиск
        if (!empty($this->hide_search) || !empty($attributes['data-url'])) {
            $attributes['data-live-search'] = 'true';
        }

        if ($this->add || $this->list) {
            //$this->route = self::$models[stripslashes($this->model)]['route'];
        }
        // создание / редактирование списка
        if ($this->add) {
            // dd($this->modeldata);
            $params = [];
            if (!empty($this->listtitle)) {
                $params['list_title'] = $this->listtitle;
            }
            if (!empty($this->where)) {
                $params['where'] = $this->where;
            }

            $attributes['data-new'] = route($this->route . '.create', $params);
        }
        if (!empty($attributes['data-new']) && empty($attributes['data-new_lbl'])) {
            $attributes['data-new_lbl'] = __('APP.ADD_VARIANT');
        }
        if ($this->list) {
            $params = [];
            if (!empty($this->listtitle)) {
                $params['list_title'] = $this->listtitle;
            }
            if (!empty($this->where)) {
                $params['where'] = $this->where;
            }
            // dd(1);
            $attributes['data-list'] = route($this->route . '.index', $params);
        }

        if (!empty($attributes['data-list']) && empty($attributes['data-list_lbl'])) {
            $attributes['data-list_lbl'] = __('APP.EDIT_LIST');
        }

        $this->attributes = $this->attributes ?: $this->newAttributeBag();
        $this->attributes->setAttributes($attributes);

        return $this;
    }
}
