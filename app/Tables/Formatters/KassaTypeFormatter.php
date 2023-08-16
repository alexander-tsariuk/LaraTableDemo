<?php
namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;

class KassaTypeFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        $servise = new MainService();
        $types = $servise->getKassaTypes();
        if (empty($types[$model->{$attribute}])) {
            return __('APP.DEADLINE_NOT_SET');
        } else {
            return $types[$model->{$attribute}];
        }
    }
}
