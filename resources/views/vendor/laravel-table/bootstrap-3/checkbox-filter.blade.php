{{-- <x-forms.input type="textarea" class="{{ $attributes->get('class') }}"
    wire:model.defer="{{ 'selectedFilters.' . $filter->identifier }}" label="{{ $filter->label }}"
    value="{{ $filter->value }}" /> --}}

<x-forms.checkbox class="{{ $attributes->get('class') }}"
    wire:model.defer="{{ 'selectedFilters.' . $filter->identifier }}" label="{{ $filter->label }}" :checked="$filter->value"
    value="1" />
