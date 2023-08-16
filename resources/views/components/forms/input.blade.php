@if ($attributes->get('type') == 'hidden')
    <input {{ $attributes->merge() }} />
@else
    <div class="form-group">
        @if (!empty($label))
            <div class="lbl">{!! html_entity_decode($label) !!}</div>
        @endif
        <div class="val">
            @if ($attributes->get('type') == 'textarea')
                <textarea {{ $attributes }}>{{ $attributes->get('value') }}</textarea>
            @else
                @if (!empty($icons))
                    <div class="input-group">
                        @foreach ($icons as $icon)
                            @if (empty($icon['position']) || $icon['position'] == 'left')
                                <div class="input-group-addon">{!! $icon['text'] !!}</div>
                            @endif
                        @endforeach
                @endif
                <input {{ $attributes->merge(['type' => 'text', 'value' => '']) }} />
                @if (!empty($icons))
                    @foreach ($icons as $icon)
                        @if (!empty($icon['position']) && $icon['position'] == 'right')
                            <div class="input-group-addon">{!! $icon['text'] !!}</div>
                        @endif
                    @endforeach
        </div>
@endif
@endif
@if ($attributes->get('help'))
    <span class="help" data-toggle="tooltip" data-placement="top" title="{{ $attributes->get('help') }}">?</span>
@endif
</div>
</div>
@endif
