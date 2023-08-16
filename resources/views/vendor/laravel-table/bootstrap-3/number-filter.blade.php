<x-forms.input class="{{ $attributes->get('class') }}" wire:model.defer="{{ 'selectedFilters.' . $filter->identifier }}"
    onchange="this.dispatchEvent(new InputEvent('input'))" label="{{ $filter->label }}" :value="!empty($filter->value) ? $filter->value : ''" />
