$.ajaxSetup({
	headers : {
		'X-CSRF-TOKEN': '{{ csrf_token() }}'
	},
	method  : "GET",
	dataType: "json"
});
$('select[select2]').each(function () {
	$(this).select2({placeholder: $(this).attr('select2') || ''})
});