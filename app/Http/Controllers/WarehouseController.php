<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends CrudController
{
    public function __construct(Request $request)
    {
        $this->model = Warehouse::class;
        $this->view = 'warehouse';
        parent::__construct();
    }

    public function update($id, Request $request)
    {
        if ($id) {
            $item = $this->model::find($id);
            $item->update($request->all());

        } else {
            $item = $this->model::create($request->all());
        }

        if($request->get('is_main') && $request->get("is_main") == 1) {
            Warehouse::where('id', '!=', $item->id)
                ->update([
                    'is_main' => 0
                ]);
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
}
