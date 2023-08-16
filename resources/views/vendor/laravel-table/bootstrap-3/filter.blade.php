<x-forms.select wire:model.defer="{{ 'selectedFilters.'.$filter->identifier }}" 
	label="{{ $label }}" 
	:options="$options" 
	:value="$filter->value" 
	choose="{{ $filter->choose }}" 
	:multiple="$filter->multiple"
	:model="!empty($filter->attrs['model']) ? $filter->attrs['model'] : false" 
	:autocomplete="!empty($filter->attrs['autocomplete']) ? true : false" 
	/>
