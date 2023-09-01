<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends BaseModel
{
    use HasFactory;

    protected $title = 'APP.WAREHOUSES';

    protected $route = 'warehouses';
}
