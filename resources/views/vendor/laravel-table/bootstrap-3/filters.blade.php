@if($searchableLabels || $filtersArray)
	<form wire:submit.prevent="$refresh" class="notajax filters {{ $searchBy || $this->selectedFilters ? '' : 'd-none' }}" >
		<div class="inner-block">
 			@csrf
        	{{-- Search --}}
        	@if($searchableLabels)
        		<div class="main-search filter">
        			<x-forms.input wire:model.defer="searchBy" label="{{ __('APP.SEARCH') }}" value="{{ $searchBy }}" />
        		</div>
        	@endif
            {{-- Filters --}}
            @if($filtersArray)
                @foreach($filtersArray as $filterArray)
                	<div class="filter">
         				{!! Okipa\LaravelTable\Abstracts\AbstractFilter::make($filterArray)->render() !!}
         			</div>
                @endforeach
            @endif
    	</div>
		<div class="btns">
			<button type="submit" class="btn btn-xs btn-success">{{ __('APP.TO_APPLY') }}</button>
			<button class="btn btn-xs bordered btn-default" wire:click.prevent="resetFilters()">{{ __('APP.RESET') }}</button>
		</div>
	</form>
 @endif