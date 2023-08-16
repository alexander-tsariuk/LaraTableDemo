{{-- Table body --}}
<tbody{!! $orderColumn ? ' wire:sortable="reorder"' : null !!}>
    {{-- Rows --}}
    @forelse($rows as $row)

        <tr wire:key="row-{{ $row->getKey() }}"{!! $orderColumn ? ' wire:sortable.item="' . $row->getKey() . '"' : null !!} @class(array_merge(Arr::get($tableRowClass, $row->laravel_table_unique_identifier, []),
                ['border-bottom']))>
            <?php $id = uniqid('td'); ?>
            <td class="text-center">
                <input id="{{ $id }}" type="checkbox" value="{{ $row->getKey() }}">
                <label for="{{ $id }}"></label>
                @if ($model)
                    <a href="{{ route($model->getRoute() . '.edit', $row->getKey()) }}" class="edit"></a>
                @endif
            </td>
            {{-- Row columns values --}}
            @foreach ($columns as $column)
                @if ($loop->first)
                    <td wire:key="cell-{{ Str::of($column->getAttribute())->snake('-')->slug() }}-{{ $model->getKey() }}"{!! $orderColumn ? ' wire:sortable.handle style="cursor: move;"' : null !!}
                        class="align-middle" scope="row">
                        {!! $orderColumn ? '<span class="mr-2">' . config('laravel-table.icon.drag_drop') . '</span>' : null !!}{{ $column->getValue($row, $tableColumnActionsArray) }}
                    </td>
                @else
                    <td wire:key="cell-{{ Str::of($column->getAttribute())->snake('-')->slug() }}-{{ $model->getKey() }}"
                        class="align-middle">

                        {{ $column->getValue($row, $tableColumnActionsArray) }}
                    </td>
                @endif
            @endforeach
        </tr>
    @empty
        <tr class="border-bottom">
            <td class="text-center " scope="row"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount + 1 . '"' : null !!}>
                <strong>{{ __('APP.DATA_NOT_FOUND') }}</strong>
            </td>
        </tr>
    @endforelse
    </tbody>
