var $modal = $('#newQuoteModal');
var $form = $modal.find('form');
var $btns = $modal.find('button');
$modal.on('show.bs.modal', function() {
	$form.find('input[name=date]').val(new Date().format("Y-m-d H:i"));
});
$modal.find('#cancelQuoteModal').on('click', function () {
	$modal.modal('hide');
	$form.trigger('reset');
	clearModalForm($form);
});
$modal.find('#addQuoteModal').on('click', function () {
	var $btn = $(this);
	$btns.attr('disabled', 'disabled');

	$.ajax({
		data      : $form.serialize(),
		url       : "/quotesboard/add",
		type      : "post",
		success   : function () {
			$btn.off('click');
			location.reload();
		},
		error     : function (data) {
			var errors = data.responseJSON;
			clearModalForm($form);
			processFormErrors($form, errors);
			$btns.attr('disabled', false);
		},
		beforeSend: function () {
			clearModalForm($form);
		}
	});
});

$('button[name="deleteQuote"]').on('click', function() {
	return confirm("Are you sure you want to delete this quote?\n\nThis process is irreversible.");
});