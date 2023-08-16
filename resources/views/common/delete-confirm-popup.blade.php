<div class="modal fade delete-common-modal" id="delete-confirm">
	<x-modal-layout title="{{ __('APP.NOTIFY') }}">
		<p class="lead">{!! __('APP.ARE_YOU_SURE_YOU_WANT_TO_DELETE') !!}</p>
     	<x-slot:footer>
     		<button type="button" class="btn btn-primary delete-common-confirm">{!! __('APP.DELETE') !!}</button>
    	</x-slot:footer>
	</x-modal-layout>
</div>
