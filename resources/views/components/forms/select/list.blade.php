<div class="modal-dialog modal-lg select-list" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ __($model->getTitle()) }}</h4>
			<x-modal.close />
		</div>
		<div class="modal-body">
			<div class="table-outer">
    			<table class="table items-table">
    				<thead>
    					<tr>
    						<th width="30px">{{ __('APP.ID') }}</th>
    						<th>{{ __('APP.TITLE') }}</th>
    						<th width="80px"></th>
    					</tr>
    				</thead>
    				@if (!empty($data))
    				<tbody>
    					@php
    					$i = 0 ;
    					@endphp
    					@foreach ($data as $value) 
    						@php
    						$i++;
    						$class = $i % 2 == 0 ? 'gray' : ''; 
    						@endphp
        					<tr class="{{ $class }}">
        						<td>{{ $value->id }}</td>
        						<td>
        							<span class="val">{{ __($value->name) }}</span>
        							<input type="text" name="name" value="{{ __($value->name) }}" />
        							<input type="hidden" name="id" value="{{ $value->id }}" />
        						</td>
        						<td class="icon text-center">
									<x-forms.select.list-edit :data="$value" :model="$model" />
        						</td>        						
         					</tr>		
     					@endforeach
    				</tbody>
    				@endif
    			</table>
			</div>
			{{ $data->links() }}
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>