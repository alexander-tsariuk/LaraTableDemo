<?php
namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;
use \App\Models\Currency;

class CurrencyLastRateFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        $servise = new MainService();
        $types = $servise->getCurrencyLastRates();
        if (empty($types[$model->{$attribute}])) {
            return __('APP.DEADLINE_NOT_SET');
        } else {
            return $types[$model->{$attribute}];
        }
    }
}
