<?php

return [

	'cdn'    => PHP_SAPI === 'cli' ? false : url('js/tinymce/tinymce.min.js'),

	'params' => [
		"selector"      => ".tinymce",
		"language"      => 'en_GB',
		"theme"         => "modern",
		"skin"          => "lightgray",
		"menubar"       => "edit insert view format table tools",
		"plugins"       => [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			"save table contextmenu directionality emoticons template paste textcolor",
		],
		"toolbar"       => "bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media fullpage | forecolor backcolor emoticons",
		"content_css"   => "/css/tinymce.css",
		"relative_urls" => false,
		"height"        => 500,
		"statusbar"    => false,
	],

];