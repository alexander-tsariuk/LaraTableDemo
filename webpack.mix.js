let mix = require('laravel-mix');
mix.combine([
	'resources/js/libraries/jquery-3.7.0.min.js',
	'resources/js/libraries/jquery-ui.min.js',
	'resources/js/libraries/bootstrap.min.js',
	'resources/js/libraries/moment-with-locales.min.js',
	'resources/js/libraries/bootstrap-datetimepicker.js',
	'resources/js/libraries/bootstrap-select.js',
	'resources/js/libraries/jquery.cookie.js',
	'resources/js/libraries/jquery.fancybox.min.js',
	'resources/js/libraries/jquery.inputmask.min.js',
	], 'public/js/libraries.js').version();
mix.combine('resources/js/app.js', 'public/js/app.js');
mix.copyDirectory('resources/css', 'public/css');
mix.copyDirectory('resources/fonts', 'public/fonts');


