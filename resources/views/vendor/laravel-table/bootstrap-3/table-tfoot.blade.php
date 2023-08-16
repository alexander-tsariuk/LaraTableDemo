{{-- Table footer--}}
<tfoot class="table-light">
    {{-- Results --}}
    @foreach($results as $result)
        <tr wire:key="result-{{ Str::of($result->getTitle())->snake('-')->slug() }}" class="border-bottom">
            <td class="align-middle fw-bold"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="px-2 py-1">{{ $result->getTitle() }}</div>
                    <div class="px-2 py-1">{{ $result->getValue() }}</div>
                </div>
            </td>
        </tr>
    @endforeach
</tfoot>