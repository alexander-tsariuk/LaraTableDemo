<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\BaseService;

class BooleanFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if (empty($model->$attribute)) {
            $html = '<span class="glyphicon glyphicon-remove"></span>';
        } else {
            $html = '<span class="glyphicon glyphicon-ok"></span>';
        }
        $html = '<div class="text-center">'.$html.'</div>';
        return $html;
    }
}
