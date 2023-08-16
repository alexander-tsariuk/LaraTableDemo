<div class="modal-dialog form-data select-modal" role="document">
	<form action="{{ route($model->getRoute().'.store') }}" class="edit-form"  method="post">
    	<div class="modal-content">
    		<div class="modal-header">
    			<h4 class="modal-title">{{ __($model->getTitle()) }}</h4>
    			<x-modal.close />
            </span>
    		</div>
    		<div class="modal-body">
             	<x-forms.input name="name" label="{{ __('APP.TITLE') }}" />
             	<x-forms.input name="code_okie" label="{{ __('APP.CODE_OKIE') }}" />
    		</div>
    		<div class="modal-footer">
    			<button type="button" class="btn btn-primary submit-modal">{{ __('APP.ADD') }}</button>
    		</div>
    	</div>
	</form>
</div>


