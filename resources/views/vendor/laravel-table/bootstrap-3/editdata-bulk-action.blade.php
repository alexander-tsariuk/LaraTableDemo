<li>
    <form id="bulkprice-{{ $bulkAction->identifier }}" class="ml-2 bulkprice-action" role="form" method="POST">
        @csrf()
        <x-forms.input type="hidden" name="ids" />
    </form>
    <a href="{{ $action }}" data-tableform="1" class="notajax open-modal red bulk-price-edit">{{ $label }}</a>
</li>
