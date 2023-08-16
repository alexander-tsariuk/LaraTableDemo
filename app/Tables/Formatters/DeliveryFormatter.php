<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;

class DeliveryFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        $servise = new MainService();
        $values = $servise->getDeliveryTypes();
        if (empty($values[$model->$attribute])) {
            return __('APP.DEADLINE_NOT_SET');
        } else {
            return $values[$model->$attribute];
        }
    }
}
