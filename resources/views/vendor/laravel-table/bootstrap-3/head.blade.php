<div class="head">
    <div class="pull-left">
        @if ($model)
            <span class="title">{{ $model->getTitle() }}</span>
            @section('title', $model->getTitle())
        @endif
        @if ($filtersArray)
            <div class="filter-btn">
                @if (!empty($this->selectedFilters) || $searchBy)
                    <span class="bullet-avatar"></span>
                @endif
                <a class="btn btn-primary show-filter notajax" href="#" data-toggle="tooltip" data-placement="bottom"
                    title="{{ __('APP.FILTERS') }}">
					<span class="glyphicon glyphicon-filter"></span>
                </a>
            </div>
        @endif
        {{-- Head action --}}
        @if ($headActionArray)
            {{ Okipa\LaravelTable\Abstracts\AbstractHeadAction::make($headActionArray)->render() }}
        @endif
        {{-- Bulk actions --}}
        @if ($tableBulkActionsArray)
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    {{ __('APP.CHANGE') }}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @foreach ($tableBulkActionsArray as $bulkActionArray)
                        {{ Okipa\LaravelTable\Abstracts\AbstractBulkAction::make($bulkActionArray)->render() }}
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- underHeadLine --}}
        @if ($underHeadLine)
            @include($underHeadLine)
        @endif
    </div>
    <div class="pull-right limits">
        {{-- Number of rows per page --}}
        @if ($numberOfRowsPerPageChoiceEnabled)
            <div wire:ignore class="inline">
                <select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="custom-select"
                    {!! (new \Illuminate\View\ComponentAttributeBag())->merge([
                            'placeholder' => __('Number of rows per page'),
                            'aria-label' => __('Number of rows per page'),
                            'aria-describedby' => 'rows-number-per-page-icon',
                            ...config('laravel-table.html_select_components_attributes'),
                        ])->toHtml() !!}>
                    @foreach ($numberOfRowsPerPageOptions as $numberOfRowsPerPageOption)
                        <option wire:key="rows-number-per-page-option-{{ $numberOfRowsPerPageOption }}"
                            value="{{ $numberOfRowsPerPageOption }}"{{ $numberOfRowsPerPageOption === $numberOfRowsPerPage ? ' selected' : null }}>
                            {{ $numberOfRowsPerPageOption }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        {{-- Settings --}}
        @if ($model && Illuminate\Support\Facades\Route::has($model->getRoute() . '.tablesettings'))
            @php
                $type = null;
                if (request()->has('type')) {
                    $type = request()->type;
                }
                if (is_null($type)) {
                    $prefix = get_class($model) . '_type';
                    $session_data = session()->get($prefix);

                    if (isset($session_data)) {
                        $type = $session_data;
                    }
                }

                $routeparams = [];

                if (!is_null($type)) {
                    $routeparams = ['type' => $type];
                }
                // dd($type);
            @endphp
            <a href="{{ route($model->getRoute() . '.tablesettings', $routeparams) }}" class="btn btn-primary notajax open-modal"
                data-toggle="tooltip" data-placement="bottom" title="{{ __('APP.TABLE_SETTINGS') }}">
                <span class="glyphicon glyphicon-cog"></span>
            </a>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
