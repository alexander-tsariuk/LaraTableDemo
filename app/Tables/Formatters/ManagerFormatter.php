<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;

class ManagerFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        if (empty($model->$attribute)) {
            return __('APP.DEADLINE_NOT_SET');
        } 
        $service = new MainService();
        $users = $service->getAllUsers();
        $results = [];
        $ids = explode(',', $model->$attribute);
        foreach ($ids as $value) {
            if (!empty($users[$value])) {
                $results[] = $users[$value]->name;
            }
        }

        return implode(', ', $results);
    }
}
