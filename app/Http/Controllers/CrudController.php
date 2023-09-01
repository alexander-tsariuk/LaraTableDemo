<?php

namespace App\Http\Controllers;

use App\Services\TableSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CrudController extends Controller {
    protected $model;

    protected $data;

    protected $view;

    protected $route;

    protected $vars = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Основная таблица
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = new $this->model();
        $data = $this->data ? $this->data : [];

        if ($request->ajax()) {
            $response['content'] = view($this->view . '.index', compact('data', 'model'))->render();
            $response['messages'] = $this->messageService->getMessages();
            if (empty(request('modal'))) {
                $params = ! empty($_GET) ? $_GET : null;
                $response['url'] = route($model->getRoute() . '.index', $params);
                $response['title'] = $model->getTitle();
            }

            if (! empty($this->vars)) {
                foreach ($this->vars as $key => $value) {
                    $response[$key] = $value;
                }
            }
            if ($request->has('result')) {
                $response['result'] = $request->result;
            }

            return response()->json($response);
        }
        $data['title'] = $model->getTitle();
        $data['model'] = $model;
        $data['data'] = $data;
        $view = $this->view . '.index';
        return view($view, compact('data', 'model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->edit(null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->update(null, $request);
    }

    /**
     * Форма редактирования записи
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id) {
            if (! empty($this->data)) {
                $data = $this->data;
            } else {
                $data = $this->model::withoutGlobalScopes()->find($id);
            }
            if (empty($data)) {
                if (request()->ajax()) {
                    return Response::json(array(
                        'error' => 404
                    ), 404);
                }
                abort(404);
            }
        } else {
            if (! empty($this->data)) {
                $data = $this->data;
            } else {
                $data = new $this->model();
            }
        }
        $vars['model'] = $data;
        // если есть что-то доп. так же добавим
        if (! empty($this->vars)) {
            $vars = array_merge_recursive($vars, $this->vars);
        }
        if (request()->ajax()) {
            $response['content'] = view($this->view . '.edit', $vars)->render();
            if (! $data->id) {
                $params = ! empty($_GET) ? $_GET : null;
                $response['url'] = route($data->getRoute() . '.create', $params);
            } else {
                $response['url'] = route($data->getRoute() . '.edit', $data->id);
            }
            $response['title'] = $data->getTitle();
            $response['messages'] = $this->messageService->getMessages();
            return response()->json($response);
        }

        return view($this->view . '.edit', $vars);
    }

    /**
     * Обновление записи
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        if ($id) {
            $item = $this->model::find($id);
            $item->update($request->all());
        } else {
            $item = $this->model::create($request->all());
        }

        if ($id) {
            $this->messageService->setMessage(__('APP.SUCCESSFULLY_UPDATED'));
        } else {
            $this->messageService->setMessage(__('APP.SUCCESSFULLY_ADDED'));
        }

        if (request()->ajax()) {
            if (! empty($request->get('save'))) {
                $response = json_decode($this->index(request())->content(), 1);
            } else {
                $response = json_decode($this->edit($item->id)->content(), 1);
            }
            $response['data'] = $item;
            return response()->json($response);
        }

        if (empty($request->get('apply_btn'))) {
            return redirect(route($item->getRoute() . '.index'));
        } else {
            return redirect(route($item->getRoute() . '.edit', $item->id));
        }
    }

    /**
     * Удаление навсегда
     *
     * @param int|string $id
     * @param Request $request
     */
    public function destroy($id)
    {
        if (is_int($id)) {
            $ids = [
                $id
            ];
        } else {
            if (is_array($id)) {
                $ids = $id;
            } else {
                $ids = explode('|', $id);
            }
        }
        $items = $this->model::whereIn('id', $ids)->get();
        if ($items->isEmpty()) {
            $this->messageService->setMessage(__('APP.OOPS_ERROR'), 'error');
            return $this->index(request());
        }
        if (count($items) > 0) {
            $items->each->forceDelete();
            $this->messageService->setMessage(__('APP.SUCCESSFULLY_DELETED'));
        }

        $request = request();
        if ($request->ajax()) {
            $request['result'] = true;
            return $this->index($request);
        }

        return redirect(route($this->route . '.index'));
    }

    /**
     * Настройки отображения таблиц для модели
     */
    public function tablesettings()
    {
        $model = str_replace('Models', 'ViewModels', $this->model);
        if (!class_exists($model)) {
            $model = $this->model;
        }
        $service = new TableSettingsService($model);
        $content = $service->show();
        $response['content'] = $content;
        $response['messages'] = $this->messageService->getMessages();

        return response()->json($response);
    }

    /**
     * Сохранение отображения таблицы для модели
     */
    public function save_tablesettings(Request $request)
    {
        $routeparams = [];
        if($request->has('type')){
            $routeparams = ['type' => $request->type];
        }
        $model = str_replace('Models', 'ViewModels', $this->model);
        if (!class_exists($model)) {
            $model = $this->model;
        }
        $service = new TableSettingsService($model);
        $service->saveColumns($request);
        $model = new $model();
        return redirect(route($model->getRoute() . '.index', $routeparams));
    }
}
