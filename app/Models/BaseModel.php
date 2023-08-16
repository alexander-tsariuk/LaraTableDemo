<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    protected $title;

    protected $route;
    
    protected $guarded = [];

    public function getRoute(): string
    {
        return $this->route ? $this->route : '';
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
    
    public function getTitle(): string
    {
        $title = $this->title ? __($this->title) : '';
        if (!empty($this->name)) {
            //$title .= ': ' . $this->name;
        }
        return $title;
    }
    
    public function setTitle($title): string
    {
        $this->title = $title;
        return $title;
    }
}