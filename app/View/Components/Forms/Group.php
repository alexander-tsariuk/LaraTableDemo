<?php

namespace App\View\Components\Forms;

use App\Services\Crm\MainService;

class Group extends Select
{
    public function __construct($model, $value = null, $label = null, $id = false) {
        $options = MainService::getGroupsByModel($model, $id);
        $link = route('crm.references.groups.index', ['type' => $model]);
        $icon = ['text' => view('components.forms.group-link', compact('link'))->render()];
        parent::__construct($label, null, $options, $value, false, false, true, false, false, false, false, false, $icon);
    }

    /**
     * Set the extra attributes that the component should make available.
     *
     * @param array $attributes
     * @return $this
     */
    public function withAttributes(array $attributes)
    {
        $attributes['choose'] = 1;
        return parent::withAttributes($attributes);
    }
}
