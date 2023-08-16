<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class SelectColumnFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if(empty($model->unit_name)) return "";

        $unit_name = __('APP.'.$model->unit_name);
        return $unit_name;
    }
}