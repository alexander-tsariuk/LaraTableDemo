<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends CrudController
{
    public function __construct(Request $request)
    {
        $this->model = Unit::class;
        $this->view = 'units';
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->data = $this->model::orderBy('name', 'asc')->paginate(50);
        return parent::index($request);
    }
}
