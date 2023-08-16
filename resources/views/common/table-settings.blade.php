<x-modal-layout title="{{ __('APP.TABLE_SETTINGS') }}" :form="['action' => $action, 'method' => 'post', 'class' => 'notajax']">
    <x-slot:footer>
        <button type="submit" class="btn reset_table">{!! __('APP.SET_DEFAULT_VALUES') !!}</button>
    </x-slot:footer>
    <input type="hidden" name="reset_table" value="0" />

    @if (isset($type) && !empty($type))
        <input type="hidden" name="type" value="{{ $type }}" />
    @endif

    <div class="table-outer">
        <table class="table lines table_settings">
            <thead>
                <tr>
                    <th width="50px;">{{ __('APP.SHOW') }}</th>
                    <th width="30px;"></th>
                    <th>{{ __('APP.TITLE') }}</th>
                    <th width="100px;">{{ __('APP.WIDTH') }}</th>
                    <th width="100px;">{{ __('APP.DEFAULT_ORDER') }}</th>
                    <th width="100px;">{{ __('APP.FILTER_ON') }}</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($columns as $key => $value) { ?>
                <?php $id = uniqid('col_'); ?>
                <tr>
                    <td class="text-center">
                        <input id="{{ $id }}" type="checkbox" name="columns[{{ $key }}][is_checked]"
                            value="1" {{ !empty($value['is_checked']) ? 'checked' : '' }} />
                        <label for="{{ $id }}"></label>
                    </td>
                    <td class="text-center">
                        <a class="notajax handle ui-sortable-handle" href="#" data-toggle="tooltip"
                            data-placement="bottom" title="{{ __('MOVE') }}">
                            <span class="glyphicon glyphicon-sort jq-radio"></span>
                        </a>
                    </td>
                    <td>
                        <input type="text" name="columns[{{ $key }}][label]"
                            value="{{ __($value['label']) }}" required="required" />
                        <input type="hidden" name="columns[{{ $key }}][column]"
                            value="{{ $value['column'] }}" />
                    </td>
                    <td>
                        <input type="text" name="columns[{{ $key }}][width]"
                            value="{{ !empty($value['width']) ? $value['width'] : '' }}" />
                    </td>
                    <td class="text-center">
                        <input type="radio" name="is_order_default" required="required"
                            {{ !empty($value['is_order_default']) ? 'checked' : '' }} value="{{ $key }}" />
                        <?php if (!empty($value['is_order_default'])) { ?>
                        <span id="order_dir">
                            <input id="{{ $key . '_asc' }}" type="radio" name="order_default_dir" value="asc"
                                {{ $value['order_default_dir'] == 'asc' ? 'checked' : '' }} />
                            <label data-toggle="tooltip" data-placement="bottom" title="{{ __('APP.ORDER_ASC') }}"
                                for="{{ $key . '_asc' }}"><span class="glyphicon glyphicon-chevron-up"></span></label>
                            <input id="{{ $key . '_desc' }}" type="radio" name="order_default_dir" value="desc"
                                {{ $value['order_default_dir'] == 'desc' ? 'checked' : '' }} />
                            <label data-toggle="tooltip" data-placement="bottom" title="{{ __('APP.ORDER_DESC') }}"
                                for="{{ $key . '_desc' }}"><span
                                    class="glyphicon glyphicon-chevron-down"></span></label>
                        </span>
                        <?php } ?>
                    </td>
                    <td class="text-center">
                        @if (!empty($value['filter_type']))
                            <x-forms.checkbox name="columns[{{ $key }}][is_show_filter]" :value="!empty($value['is_show_filter']) ? $value['is_show_filter'] : 1"
                                :checked="!empty($value['is_show_filter']) ? $value['is_show_filter'] : false" />
                        @endif
                    </td>
                </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        $('.table_settings tbody').sortable({
            handle: '.handle',
        })
        $('[name="is_order_default"]').change(function() {
            $(this).after($('#order_dir'));
        })
        $('.reset_table').click(function() {
            $('[name="reset_table"]').val(1);
        })
    </script>
</x-modal-layout>
