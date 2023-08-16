<div class="head">
    @if ($title)
        <span class="title">{!! $title !!}</span>
        @section('title', $title)
    @endif

    <x-forms.apply-btn />
    <x-forms.close-btn url="{{ $back }}" />
    <?php if ($model->getRoute() == 'crm.documents' &&  $model->type == 1 && !empty($settings['products']['retail_order'])) { ?>
    <div class="order_type_choose">
        <label>
            <?php $checked = $model->order_type == 1 ? 'checked' : ''; ?>
            <input type="radio" name="order_type" value="1" <?php echo $checked; ?> />
            <?php echo __('APP.SUPPLIER_ORDER_TYPE_1'); ?>
        </label>
        <label>
            {{-- {{dd($model->order_type)}} --}}
            <?php $checked = $model->order_type == 2 ? 'checked' : ''; ?>
            <input type="radio" name="order_type" value="2" <?php echo $checked; ?> />
            <?php echo __('APP.SUPPLIER_ORDER_TYPE_2'); ?>
        </label>
    </div>
    <?php } ?>

</div>
