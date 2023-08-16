<?php

namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use App\Services\Crm\MainService;

class OrganizationFormatter extends AbstractFormatter
{
    public function format(Model $model, string $attribute): string
    {
        $value = '';
        $service = new MainService();
        $orgs = $service->getAllOrgs();

        if (!empty($orgs[$model->organization_id]))
            $value = $orgs[$model->organization_id];

        return $value;
    }
}
