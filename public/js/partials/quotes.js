var $modal = $('#newQuoteModal');
var $form = $modal.find('form');
var $btns = $modal.find('button');
$modal.on('show.bs.modal', function() {
	$form.trigger('reset');
	clearModalForm($form);
	$form.find('input[name=date]').val(new Date().format("Y-m-d H:i"));
	$form.attr('action', '/quotesboard/add');
});
$modal.find('#addQuoteModal').on('click', function () {
	submitForm($form, $(this));
});

$('button[name="deleteQuote"]').on('click', function() {
	return confirm("Are you sure you want to delete this quote?\n\nThis process is irreversible.");
});