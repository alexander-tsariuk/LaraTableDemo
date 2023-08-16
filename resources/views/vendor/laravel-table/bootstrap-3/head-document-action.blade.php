<div class="btn-group" role="group">

    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
        data-toggle="tooltip" data-placement="bottom" title="<?php echo __('APP.DOCUMENT'); ?>">
        <span class="" data-toggle="tooltip" data-placement="bottom" title="<?php echo __('APP.ADD'); ?>">
            <span class="svg-icon">
                <svg class="svg-plus">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-plus"></use>
                </svg>
            </span>
        </span>
    </button>

    <ul class="dropdown-menu blocks documents-dropdown">
        <li>
            <div class="title"><?php echo __('APP.STOCK'); ?></div>
            <ul>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 9]) }}" class=""><?php echo __('APP.DOC_TYPE_9'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 8]) }}" class=""><?php echo __('APP.DOC_TYPE_8'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 6]) }}" class=""><?php echo __('APP.DOC_TYPE_6'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 7]) }}" class=""><?php echo __('APP.DOC_TYPE_7'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 4]) }}" class=""><?php echo __('APP.DOC_TYPE_4'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 11]) }}" class=""><?php echo __('APP.DOC_TYPE_11'); ?></a>
                </li>
                <li class="column1"><a href="{{ route('crm.documents.create', ['type' => 12]) }}" class=""><?php echo __('APP.DOC_TYPE_12'); ?></a>
                </li>
            </ul>
        </li>
        <li>
            <div class="title"><?php echo __('APP.PROCUREMENT'); ?></div>
            <ul>
                <li class="column2 reset"><a href="{{ route('crm.documents.create', ['type' => 1]) }}" class=""><?php echo __('APP.DOC_TYPE_1'); ?></a>
                </li>
                <li class="column2"><a href="{{ route('crm.documents.create', ['type' => 2]) }}" class=""><?php echo __('APP.DOC_TYPE_2'); ?></a>
                </li>
                <li class="column2"><a href="{{ route('crm.documents.create', ['type' => 3]) }}" class=""><?php echo __('APP.DOC_TYPE_3'); ?></a>
                </li>
            </ul>
        </li>
        <li>
            <div class="title"><?php echo __('APP.SALES'); ?></div>
            <ul>
                <li class="column3 reset"><a href="{{ route('crm.documents.create', ['type' => 5]) }}"
                        class=""><?php echo __('APP.DOC_TYPE_5'); ?></a>
                </li>
                <li class="column3"><a href="{{ route('crm.documents.create', ['type' => 10]) }}"
                        class=""><?php echo __('APP.DOC_TYPE_10'); ?></a>
                </li>

                <li class="column3"><a href="{{ route('crm.documents.create', ['type' => 13]) }}"
                        class=""><?php echo __('APP.DOC_TYPE_13'); ?></a>
                </li>
            </ul>
            <ul>
                <li class="column3"><a href="{{ route('crm.documents.create', ['type' => 14]) }}"
                        class=""><?php echo __('APP.DOC_TYPE_14'); ?></a>
                </li>           	
            </ul> 
        </li>
    </ul>
</div>
