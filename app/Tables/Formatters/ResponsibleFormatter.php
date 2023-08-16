<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;

class ResponsibleFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        $value = '';
        $service = new MainService();
        $users = $service->getAllUsers();

        if (!empty($users[$model->author_id]))
            $value = $users[$model->author_id]->name;


        return $value;
    }
}
