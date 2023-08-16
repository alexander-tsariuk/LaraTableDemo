<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class ValueYesNoFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if(empty($model->mod_count)) return __('APP._NOT');

        return $model->mod_count;
    }
}
