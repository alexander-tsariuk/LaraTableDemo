<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class DateTimeFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if (empty($model->$attribute))
            return '';
        $date = strtotime($model->$attribute);
        $date = date('d.m.Y H:i', $date);
        return $date;

    }
}
