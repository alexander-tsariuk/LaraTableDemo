@section('title', __('APP.EDIT_USLUG'))
<x-app-layout>
    <x-forms.main :model="$model">
        <div class="form-data row">
            <div class="col-xs-12 col-md-6">
				<x-forms.input name="name" label="{{ __('APP.TITLE') }}" required="required" value="{{ $model->name }}" />
				<div class="row">
					 <div class="col-xs-12 col-md-6">
					 	<x-forms.input name="vendor_code" label="{{ __('APP.VENDOR_CODE') }}" required="required" value="{{ $model->vendor_code }}" />
					 </div>					 
					 <div class="col-xs-12 col-md-6">
					 	<x-forms.select name="unit_id" label="{{ __('APP.UNIT') }}"  value="{{ $model->unit_id }}" model="App\Models\Unit" add="1" list="1" />
					 </div>
				</div>				
				
				
				<div class="row">
					 <div class="col-xs-12 col-md-6">
					 	<x-forms.input class="number" name="buy_price" label="{{ __('APP.COST_ZAK') }}" value="{{ $model->buy_price }}" />
					 </div>					 
					 <div class="col-xs-12 col-md-6">
					 	<x-forms.input class="number" name="sale_price" label="{{ __('APP.PROD') }}"  value="{{ $model->sale_price }}" />
					 </div>
				</div>
			</div>
		</div>
    </x-forms.main>
</x-app-layout>
