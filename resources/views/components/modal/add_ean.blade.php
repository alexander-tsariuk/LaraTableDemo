<div id="add_by_ean" class="modal fade ">
    <x-modal-layout title="{{ __('APP.ADD_BY_EAN') }}">
    	<x-forms.input id="add_ean" label="{{ __('APP.CODE_EAN') }}" />
    	<x-slot:footer>
    		<x-forms.checkbox label="{{ __('APP.ADD_BY_EAN_POSITION') }}" class="add_new_pos" />
    		<button id="submit_ean" type="button" class="btn btn-primary">{!!__('APP.ADD') !!}</button>
    	</x-slot:footer>
    </x-modal-layout>
</div>