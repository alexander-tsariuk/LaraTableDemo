<div class="summ-range">
    <x-forms.input class="{{ $attributes->get('class') }}" wire:model.defer="{{ 'selectedFilters.' . $filter->identifier .'.from' }}"
		onchange="this.dispatchEvent(new InputEvent('input'))"
        label="{{ __('APP.AMOUNT_FROM') }}"  :value="!empty($filter->value['from']) ? $filter->value['from'] : ''"  />
    <x-forms.input class="{{ $attributes->get('class') }}" wire:model.defer="{{ 'selectedFilters.' . $filter->identifier .'.to' }}"
        label="{{ __('APP.AMOUNT_TO') }}" :value="!empty($filter->value['to']) ? $filter->value['to'] : ''"
        onchange="this.dispatchEvent(new InputEvent('input'))" />
</div>
