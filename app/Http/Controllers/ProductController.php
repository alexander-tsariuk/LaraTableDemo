<?php

namespace App\Http\Controllers;

use App\Models\Uslug;
use Illuminate\Http\Request;

class ProductController extends CrudController
{
    public function index(Request $request)
    {
        return view('products.index');
    }
}
