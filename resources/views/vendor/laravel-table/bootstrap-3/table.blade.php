<div class="table-form">
	@include(config('laravel-table.head', 'vendor.laravel-table.bootstrap-3.head'))
	@include(config('laravel-table.filters', 'vendor.laravel-table.bootstrap-3.filters'))
    <div class="table-responsive table-outer">
        <table class="table table-hover del-table mobile_table table-striped">
			@include(config('laravel-table.table.head', 'vendor.laravel-table.bootstrap-3.table-head'))
			@include(config('laravel-table.table.body', 'vendor.laravel-table.bootstrap-3.table-body'))
			@include(config('laravel-table.table.tfoot', 'vendor.laravel-table.bootstrap-3.table-tfoot'))
        </table>
    </div>
	<div class="pages">
		{!! $rows->links() !!}
	</div>
    @if ($params = request()->request->all('table_params'))
    	<div class="table_params">
    	@foreach ($params as $key => $param)
    		@if (is_array($param))
    			@foreach ($param as $k => $p)
    				<input type="hidden" wire:model.defer="request_params.{{ $key.'.'.$k }}" data-value="{{ $p }}" onchange="this.dispatchEvent(new InputEvent('input'))" />
    			@endforeach
    		@else
        		<input type="hidden" wire:model.defer="request_params.{{ $key }}" data-value="{{ $param }}" onchange="this.dispatchEvent(new InputEvent('input'))" />
        	@endif
        @endforeach
        </div>
        <script>
        document.addEventListener('livewire:load', function() {
            $.each($('.table_params input'), function(i,v){
                $(v).val($(v).data('value'));
                $(v).trigger('change');
            })           
        })
        </script>
    @endif
</div>
<script>
@if (request()->ajax())
	window.livewire.start();
@endif
</script>

