<span class="c_radio">
    <input type="radio" id="{{ $id }}" {{ $attributes }} @checked($checked) />
    @if ($label)
    	<label for="{{ $id }}">{!! $label !!}</label>
    @endif   
</span>