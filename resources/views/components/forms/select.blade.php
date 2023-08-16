<div class="form-group">
    @if (!empty($label))
        <div class="lbl">{!! html_entity_decode($label) !!}</div>
    @endif
    <div class="val">
		@if (!empty($icons))
			<div class="input-group select_group">
			@foreach ($icons as $icon)
				@if (empty($icon['position']) || $icon['position'] == 'left')
					<div class="input-group-addon">{!! $icon['text'] !!}</div>
				@endif
			@endforeach
		@endif
        {{-- {{dd($attributes)}} --}}
        <select {{ !empty($attributes) ? $attributes->merge(['value' => '']) : '' }}>
            @if (!empty($attributes) && $attributes->has('placeholder'))
                <option>{{ $attributes->get('placeholder') }}</option>
            @endif
            @if (!empty($options))
                @foreach ($options as $option)
                    @if (isset($option->value))
                        <option @selected(isset($option->value) && in_array($option->value, $value) ? 1 : 0)
                    		@disabled(!empty($option->disabled) ? 1 : 0)
                          	data-object='{!! json_encode($option) !!}'
                            value="{{ $option->value }}" data-content="{{ $option->name }}">{{ $option->name }}</option>
                    @endif
                @endforeach
            @endif
        </select>
		@if (!empty($icons))
			@foreach ($icons as $icon)
				@if (!empty($icon['position']) && $icon['position'] == 'right')
					<div class="input-group-addon">{!! $icon['text'] !!}</div>
				@endif
			@endforeach
			</div>
		@endif

    </div>
</div>
