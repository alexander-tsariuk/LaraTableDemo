<div class="form-group switcher-block">
    @if ($attributes->get('label'))
        <div class="lbl">{!! $attributes->get('label') !!}</div>
    @endif
    <div class="val">
         <div class="switcher ">
            <label class="switch {{ $attributes->get('class') }}">
              <input {{ $attributes->merge(['type' => 'checkbox']) }} value="1">
              <span class="slider clearfix gray">
                <span class="on pull-left">{{ mb_strtolower(__('APP._YES')) }}</span>
                <span class="off pull-right">{{ mb_strtolower(__('APP._NOT')) }}</span>
               </span>
            </label>
         </div>   
     </div>
</div>