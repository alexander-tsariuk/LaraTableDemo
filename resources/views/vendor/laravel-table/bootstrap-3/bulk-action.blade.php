<li wire:key="{{ Str::of($bulkAction->identifier)->snake('-')->slug() }}">
    <a href="#" class="notajax check-checked" wire:click.prevent="bulkAction('{{ $bulkAction->identifier }}', {{ $bulkAction->getConfirmationQuestion() ? 1 : 0 }})">
        {{ $label }}
    </a>
</li>


