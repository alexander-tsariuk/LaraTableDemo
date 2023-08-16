<?php
namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;
use \App\Models\Currency;

class CurrencyNameFormatter extends AbstractFormatter
{
    protected $types = [];

    public function format(Model $model, string $attribute): string
    {
        if (count($this->types) == 0) {
            $servise = new MainService();
            $this->types = $servise->getCurrencyNames();
        }
        if (empty($this->types[$model->{$attribute}])) {
            return __('APP.DEADLINE_NOT_SET');
        } else {
            return $this->types[$model->{$attribute}];
        }

    }
}
