<script type="text/javascript" src="{{ config('tinymce.cdn') }}"></script>
<script type="text/javascript">
	tinymce.init(
		{!! json_encode(config('tinymce.params')) !!}
	);
 </script>
