$(function() {
	$(document).on('click', 'a[id=btn-show-detail]', show_detail);


	function show_detail() {
		let key = this.dataset.key;
		window.location.href = "detail?ident_id=" + key;
	}
});