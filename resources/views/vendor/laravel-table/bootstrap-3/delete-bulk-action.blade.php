<li>
    <form id="destroy-{{ $bulkAction->identifier }}" class="ml-2 destroy-action" role="form" method="POST"
        action="{{ $action }}">
        @csrf()
        @method('DELETE')
        <x-forms.input type="hidden" name="id" />
        <x-forms.input type="hidden" name="type" value="{{ request('type') }}" />
    </form>
    <a href="#" class="notajax delete red" data-toggle="modal" data-target="#delete-confirm">{{ $label }}</a>
</li>
