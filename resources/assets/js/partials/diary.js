$modal.on('click', '#submitDateModal', function () {
	$modal.find('button').attr('disabled', 'disabled');
	var form = $modal.find('form');
	window.location = $(this).data('url')
		.replace('%year', form.find('select[name="year"]').val())
		.replace('%month', form.find('select[name="month"]').val());
});