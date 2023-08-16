<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="theme-color" content="#468996">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <base href="{{ URL::to('/') }}" />
    <meta name="Description" content="{!! __('APP.OG_DESCRIPTION') !!}" />
    <meta name="Keywords" content="{!! __('APP.OG_KEYWORDS') !!}" />
    <link rel="shortcut icon" href="/favicon.ico">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flasher.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    
	
    <!-- Scripts -->
    <script src="{{ asset('js/libraries.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

</head>
<body class="auth">
    <div class="container">
    	@include('layouts.top')
    	<div id="main-content">
        {{ $slot }}
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal"></div>
    @include('common.delete-confirm-popup')
    @stack('scripts')
    @livewireScripts
    @if (!empty($messages))
        <script>
            $(document).ready(function() {
                var data = {};
                data.messages = JSON.parse('{!! json_encode($messages) !!}')
                showMsgs(data);
            });
        </script>
    @endif
</body>
</html>
