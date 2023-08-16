@section('title', __('APP.SERVICES'))
<x-app-layout>
	<livewire:table :config="App\Tables\Uslugs::class"/>
</x-app-layout>