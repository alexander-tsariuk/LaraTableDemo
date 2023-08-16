<x-forms.input type="textarea" class="{{ $attributes->get('class') }}"  wire:model.defer="{{ 'selectedFilters.'.$filter->identifier }}"  label="{{ $filter->label }}" value="{{ $filter->value }}" />
