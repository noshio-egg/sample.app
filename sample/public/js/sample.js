/**
 * DOM読込み終了時
 *
 * @returns
 */
$(document).ready(function() {
	let $message = $('#message-area');
	let type = $message.data('type');
	switch (type) {
	case 2:
		$message.addClass('alert-warning');
		break;
	case 3:
		$message.addClass('alert-danger');
		break;
	default:
		$message.addClass('alert-info');
		break;
	}
});

$(function() {
	/**
	 * イベントマッピング
	 */
	$('#user-list').on('click', 'tr[id=user-row]', show_detail);
	$('#update-area').on('click', 'button[id=delete-row]', delete_row);

	/**
	 * 更新/削除エリアの表示
	 */
	function show_detail() {
		document.forms.update_form.id.value = $(this).data('id');
		document.forms.update_form.name.value = $(this).data('name');
		document.forms.update_form.gender.value = $(this).data('gender');
		document.forms.update_form.birthday.value = $(this).data('birthday');
		document.forms.update_form.remarks.value = $(this).data('remarks');
		let $elm = $('#update-area');
		$elm.removeClass('invisible');
		$elm.addClass('visible');
	}

	/**
	 * 削除処理リクエスト
	 */
	function delete_row() {
		document.forms.delete_form.id.value = document.forms.update_form.id.value;
		$('#delete_form').submit();
	}
});
