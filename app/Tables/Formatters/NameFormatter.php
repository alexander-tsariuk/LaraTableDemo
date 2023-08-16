<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class NameFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if (!method_exists($model, 'getRoute') || request('modal')) {
            return $model->name;
        }
        $link = route($model->getRoute().'.edit', $model->id);
        $link = '<a href="'.$link.'">'.$model->name.'</a>';
        return $link;
    }
}
