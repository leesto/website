function clearModalForm($form) {
	$form.find('.has-error,.has-success');
	$form.find('.has-error').removeClass('has-error').find('p.errormsg').remove();
	$form.find('.has-success').removeClass('has-success');
}
function processFormErrors(form, errors) {
	if(typeof(errors.responseJSON) == "object") {
		errors = errors.responseJSON;
		if(typeof(errors.error) == 'string') {
			alert("Sorry, an error occurred\n\nDetails: " + errors.error);
		} else {
			form.find('input,textarea,select').each(function () {
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
		console.log(errors.responseText);
	}
}
function submitForm($form, $btn) {
	$btns = $form.find('button');
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
			clearModalForm($form);
			processFormErrors($form, data);
			$btns.attr('disabled', false);
		},
		beforeSend: function () {
			clearModalForm($form);
		}
	});
}
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