<?php
namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;


class LangFormatter extends AbstractFormatter
{

    public function format(Model $model, string $attribute): string
    {
        $value = !empty($model->$attribute) ? $model->$attribute : '';
        if (strpos($model->$attribute, 'APP.') !== false) {
            $value = __($value);
        }
        return $value;

    }
}
