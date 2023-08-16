<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends BaseModel
{
    use HasFactory;

    protected $title = 'APP.UNIT';

    protected $route = 'units';

    protected $fillable = [
        'name',
        'code_okie'
    ];
}
