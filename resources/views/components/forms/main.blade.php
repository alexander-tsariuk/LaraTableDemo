@php
if ($model->id) {
    $action = route($model->getRoute().'.update', $model->id);
} else {
    $action = route($model->getRoute().'.store');
}
@endphp
<form action="{{ $action }}" class="edit-form" method="post">
	@if (empty($attributes->get('not_head')))
		<x-forms.head :model="$model" title="{{ $model->getTitle() }}" back="{{ empty($back) ? route($model->getRoute().'.index') : $back }}" />
	@endif
	{{ $slot }}
	@csrf
	<input type="hidden" id="item_id" name="id" value="{{ $model->id }}" />
	@if (!empty($model->id))
		{{ method_field('PUT') }}
	@endif
	<x-forms.edit-notify />
</form>
