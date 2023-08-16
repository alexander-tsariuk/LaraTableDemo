<span class="edit-btns">
 	<a class="save notajax" href="{{ route($model->getRoute().'.update', $data->id) }}">
		<span class="glyphicon glyphicon-ok"></span>
	</a>   	
	 | 
	<a href="#" class="close-edit-history notajax">
		<span class="glyphicon glyphicon-remove"></span>
	</a> 								
</span>
<span class="val">
	@if (empty($value->no_change))
	<a class="edit-history notajax" href="#">
		<svg class="svg-edit"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-edit"></use></svg>
	</a>	
	@endif
	@if (empty($value->no_delete)) 
	<a href="{{ route($model->getRoute().'.destroy', $data->id) }}" class="remove-element notajax remove-select" data-id="{{ $data->id }}" data-element="tr">
		<svg class=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cart"></use></svg>
	</a>
	@endif
</span>