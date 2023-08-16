<div {{ $attributes->merge(['class' => 'modal-dialog', 'role' => 'document']) }} >
	@if (!empty($form))
		<form action="{{ $form['action'] }}" method="{{ $form['method'] }}" class="{{ $form['class'] }}">
		@csrf
		@method($form['method'])
	@endif
	<div class="modal-content">
		<div class="modal-header">
			<x-modal.close />
			@if (!empty($title))
				<h4 class="modal-title">{!! $title !!}</h4>
			@endif
		</div>
		<div class="modal-body">
			{{ $slot }}
		</div>
		<div class="modal-footer">
			{!! $footer !!}
			@if (!empty($form))
				<button type="submit" class="btn btn-success">{{ $form['btn'] }}</button>
			@endif
			<button type="button" class="btn btn-primary" data-dismiss="modal">{{ $close }}</button>
		</div>
	</div>
	@if (!empty($form))
		</form>
	@endif
</div>