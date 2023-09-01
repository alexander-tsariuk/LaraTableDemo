@section('title', __('APP.WAREHOUSES'))
<x-app-layout>
	<livewire:table :config="App\Tables\Warehouses::class"/>
</x-app-layout>
