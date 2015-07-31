var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;
elixir.config.autoprefix = false;
elixir(function (mix) {
	mix.sass('app/app.scss', 'resources/assets/css/app.css')
		.sass('app/partials/quotes.scss', 'public/css/partials/quotes.css')
		.sass('app/partials/polls.scss', 'public/css/partials/polls.css')
		.sass('tinymce/tinymce.scss', 'public/css/tinymce.css')
		.sass('font-awesome/font-awesome.scss', 'resources/assets/css/font-awesome.css')
		.styles([
			'reset.css',
			'bootstrap.min.css',
			'bootstrap-theme.min.css',
			'select2.min.css',
			'font-awesome.css',
			'app.css'
		], 'public/css/app.css')
		.scripts([
			'date.format.js',
			'jquery.js',
			'jquery.CloseMessages.js',
			'jquery.DisableSubmitButtons.js',
			'jquery.ModalPopup.js',
			'bootstrap.min.js',
			'select2.min.js',
		], 'public/js/app.js')
		.copy('resources/assets/js/tinymce', 'public/js/tinymce')
		.copy('resources/assets/js/partials', 'public/js/partials');
});
