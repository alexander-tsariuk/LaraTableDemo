{{-- Table header --}}
<thead>
    {{-- Column headings --}}
    <tr class="table-light border-top border-bottom">
        <th width="50px" class="text-center" scope="col">
            <div class="d-flex align-items-center">
                {{-- Bulk actions select all --}}
                <?php
                $id = uniqid('ch');
                $columnsCount++;
                ?>
                <input id="{{ $id }}" class="mr-1 checkall" type="checkbox"
                    aria-label="Check all displayed lines">
                <label for="{{ $id }}"></label>
            </div>
        </th>
        {{-- Sorting/Column titles --}}
        @foreach ($columns as $column)
            <th wire:key="column-{{ Str::of($column->getAttribute())->snake('-')->slug() }}" class="align-middle"
                scope="col" {{ !empty($column->width) ? 'style=width:' . $column->width : '' }}>
                @if ($column->isSortable($orderColumn))
                    @if ($sortBy === $column->getAttribute())
                        <a wire:click.prevent="sortBy('{{ $column->getAttribute() }}')"
                            class="d-flex align-items-center notajax" href=""
                            title="{{ $sortDir === 'asc' ? __('APP.ORDER_DESC') : __('APP.ORDER_ASC') }}"
                            data-toggle="tooltip">
                            {!! $sortDir === 'asc' ? config('laravel-table.icon.sort_desc') : config('laravel-table.icon.sort_asc') !!}
                            <span class="ml-2">{{ $column->getTitle() }}</span>
                        </a>
                    @else
                        <a wire:click.prevent="sortBy('{{ $column->getAttribute() }}')"
                            class="d-flex align-items-center notajax" href="" title="{{ __('Sort ascending') }}"
                            data-toggle="tooltip">
                            {!! config('laravel-table.icon.sort') !!}
                            <span class="ml-2">{{ $column->getTitle() }}</span>
                        </a>
                    @endif
                @else
                    {{ $column->getTitle() }}
                @endif
            </th>
        @endforeach
        {{-- Row actions --}}
        @if ($tableRowActionsArray)
            <th wire:key="row-actions" class="align-middle text-end" scope="col">
                {{ __('Actions') }}
            </th>
        @endif
    </tr>
</thead>
