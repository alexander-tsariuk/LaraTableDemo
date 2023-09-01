@section('title', __('APP.EDIT_WAREHOUSE'))
<x-app-layout>
    <x-forms.main :model="$model">
        <div class="form-data row">
            <div class="col-xs-12 col-md-6">
				<x-forms.input name="name" label="{{ __('APP.TITLE') }}" required="required" value="{{ $model->name }}" />
				<div class="row">
                    @if(!empty($model->id))
                         <div class="col-xs-12 col-md-6">
                            <x-forms.input name="id" label="{{ __('APP.CODE') }}"  value="{{ $model->id }}" readonly=""/>
                         </div>
                    @endif

					 <div class="col-xs-12 col-md-6">
                         <x-forms.select name="is_main" label="{{ __('APP.IS_MAIN') }}"  value="{{ $model->is_main }}" :options="[0 => 'Нет', 1 => 'Да']"/>
                     </div>
				</div>

			</div>
		</div>
    </x-forms.main>
</x-app-layout>
