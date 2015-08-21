var $modal = $('#roleModal');
var $form = $modal.find('form');
var $btns = $modal.find('button');
$modal.on('show.bs.modal', function (event) {
	clearModalForm($form);
	var btn = $(event.relatedTarget);
	$form.attr("action", btn.data("url"));
	$btns.attr('disabled', false);
	$form.find('select[name=order]').find('option').removeAttr('disabled');

	if(btn.data('method') == 'edit') {
		$modal.find('.modal-header > h1').text('Edit a Committee Role');
		$modal.find('#modalSubmit').children('span:first').attr('class', 'fa fa-refresh');
		$modal.find('#modalSubmit').children('span:last').text('Save changes');
		$modal.find('#modalDelete').show();
		$form.find('input[name=name]').val(btn.data('roleName'));
		$form.find('input[name=email]').val(btn.data('roleEmail'));
		$form.find('input[name=id]').val(btn.data('roleId'));
		$form.find('textarea[name=description]').val(btn.data('roleDesc'));
		$form.find('select[name=user_id]').val(btn.data('roleUserId'));
		$form.find('select[name=order]').val(btn.data('roleOrder'));
		$form.find('select[name=order]').find('option[value=' + (btn.data('roleOrder') + 1)+ ']').attr('disabled', 'disabled');
	} else if(btn.data('method') == 'add') {
		$modal.find('.modal-header > h1').text('Add a Committee Role');
		$modal.find('#modalSubmit').children('span:first').attr('class', 'fa fa-plus');
		$modal.find('#modalSubmit').children('span:last').text('Add role');
		$modal.find('#modalDelete').hide();
	} else {
		$modal.modal('close');
	}
});
$modal.find('#modalCancel').on('click', function () {
	$modal.modal('hide');
	$form.trigger('reset');
});
$modal.find('#modalDelete').on('click', function () {
	$btns.attr('disabled', 'disabled');
	$.ajax({
		data   : 'id=' + $form.find('input[name=id]').val(),
		url    : '/committee/delete',
		type   : 'post',
		success: function () {
			location.reload();
		},
		error  : function () {
			alert("An error occurred while trying to delete the selected committee role.");
			$btns.attr('disabled', false);
		}
	})
});
$modal.find('#modalSubmit').on('click', function () {
	var $btn = $(this);
	$btns.attr('disabled', 'disabled');

	$.ajax({
		data      : $form.serialize(),
		url       : $form.attr('action'),
		type      : "post",
		success   : function () {
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
});