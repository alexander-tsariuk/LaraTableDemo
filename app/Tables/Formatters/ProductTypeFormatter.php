<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class ProductTypeFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if(empty($model->type_name)) return "";

        $type_name = __('APP.'.$model->type_name);
        return $type_name;
    }
}
