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
elixir(function (mix) {
	mix.sass('app/app.scss', 'resources/assets/css/app.css')
		.sass('app/partials/committee.scss', 'public/css/partials/committee.css')
		.sass('app/partials/equipment.scss', 'public/css/partials/equipment.css')
		.sass('app/partials/events.scss', 'public/css/partials/events.css')
		.sass('app/partials/gallery.scss', 'public/css/partials/gallery.css')
		.sass('app/partials/members.scss', 'public/css/partials/members.css')
		.sass('app/partials/quotes.scss', 'public/css/partials/quotes.css')
		.sass('app/partials/polls.scss', 'public/css/partials/polls.css')
		.sass('app/partials/users.scss', 'public/css/partials/users.css')
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
			'jquery.tabify.js',
			'jquery.CloseMessages.js',
			'jquery.DisableSubmitButtons.js',
			'bootstrap.min.js',
			'select2.min.js',
			'app.js',
		], 'public/js/app.js')
		.copy('resources/assets/js/tinymce', 'public/js/tinymce')
		.copy('resources/assets/js/partials', 'public/js/partials');
});
