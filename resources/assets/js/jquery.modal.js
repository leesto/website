var $modal = $('#modal');
var $modalDialog = $modal.children('div.modal-dialog');
var $modalContent = $modalDialog.children('div.modal-content');
var $form;
var $btns;
(function ($) {
	function form() {
		return $modal.find('form');
	}
	function btns() {
		return $modal.find('button');
	}

	$modal.on('show.bs.modal', function (e) {
		var target = $(e.relatedTarget);
		var template = $('div[data-type="modal-template"][data-id="' + target.data('modalTemplate') + '"]');
		if(template.length > 0) {
			$modalContent.html(template.html());
			if(target.data('modalClass')) {
				$modalDialog.addClass(target.data('modalClass'));
			}
			if(target.data('modalTitle')) {
				if($modalContent.children('div.modal-header').length == 0) {
					$modalContent.prepend('<div class="modal-header"></div>');
				}
				$modalContent.children('div.modal-header').html('<h1>' + target.data('modalTitle') + '</h1>');
			}
		} else {
			$modal.one('shown.bs.modal', function () {
				$modal.modal('hide');
			});
		}
	});
	$modal.on('hidden.bs.modal', function () {
		$modalDialog.attr('class', 'modal-dialog');
		$modalContent.html('');
	});
	$modal.on('clearform', function () {
		$form = form();
		$form.find('.has-error').removeClass('has-error').find('p.errormsg').remove();
		$form.find('.has-success').removeClass('has-success');
	});
	$modal.on('click', '[data-type="submit-modal"]', function() {
		if(!$(this).data('submitConfirm') || confirm($(this).data('submitConfirm'))) {
			$modal.trigger('ajaxsubmit', {
				btn: $(this)
			});
		}
	});
	$modal.on('ajaxsubmit', function (event, data) {
		var $btn = data.btn;
		$form = form();
		$btns = btns();

		$form.attr('action', $btn.data('formAction'));
		$btns.attr('disabled', 'disabled');
		$.ajax({
			data      : $form.serialize(),
			url       : $form.attr('action'),
			type      : "post",
			success   : function () {
				$btn.off('click');
				location.reload();
			},
			error     : function (data) {
				$modal.trigger('clearform');
				$btns.attr('disabled', false);

				if(typeof(data.responseJSON) == "object") {
					var errors = data.responseJSON;
					if(typeof(errors.error) == 'string') {
						alert("Sorry, an error occurred\n\nDetails: " + errors.error);
					} else {
						$form.find('input,textarea,select').each(function () {
							var $input = $(this);
							var $group = $input.parents('.form-group');
							if($input.attr('name') in errors) {
								$group.addClass('has-error');
								if($input.parent().hasClass('input-group')) {
									$('<p class="help-block errormsg">' + errors[$input.attr('name')][0] + '</p>').insertAfter($input.parent());
								} else {
									$('<p class="help-block errormsg">' + errors[$input.attr('name')][0] + '</p>').insertAfter($input);
								}
							} else {
								$group.addClass('has-success');
							}
						});
					}
				} else {
					alert("Oops, an unknown error has occurred");
					console.log(data.responseText);
				}
			},
			beforeSend: function () {
				$modal.trigger('clearform');
			}
		});
	});
})(jQuery);