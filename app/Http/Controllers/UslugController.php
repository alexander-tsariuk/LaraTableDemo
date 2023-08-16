<?php

namespace App\Http\Controllers;

use App\Models\Uslug;
use Illuminate\Http\Request;

class UslugController extends Controller
{
    public function __construct(Request $request)
    {
        $this->model = Uslug::class;
        $this->view = 'uslugs';
        parent::__construct();
    }
}
