function clearModalForm($form)
{
	$form.find('.has-error,.has-success');
	$form.find('.has-error').removeClass('has-error').children('p.errormsg').remove();
	$form.find('.has-success').removeClass('has-success');
}

$('#newQuoteModal').on('shown.bs.modal', function () {
	var $modal = $(this);
	var $form = $modal.find('form');
	var $btns = $modal.find('button');
	$form.find('input[name=date]').val(new Date().format("Y-m-d H:i"));
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
				if(typeof(errors) == "object") {
					$form.find('input[type=text],textarea').each(function () {
						var $input = $(this);
						var $group = $input.parents('.form-group');
						if($input.attr('name') in errors) {
							$group.addClass('has-error');
							$group.append('<p class="help-block errormsg">' + errors[$input.attr('name')][0] + '</p>');
						} else {
							$group.addClass('has-success');
						}
					});
				} else {
					alert(errors);
				}
				$btns.attr('disabled', false);
			},
			beforeSend: function () {
				clearModalForm($form);
			}
		});
	});
});

$('button[name="deleteQuote"]').on('click', function() {
	return confirm("Are you sure you want to delete this quote?\n\nThis process is irreversible.");
});