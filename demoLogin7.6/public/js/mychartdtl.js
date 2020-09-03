$(function(event) {

	window.myChart = [];
	$('#btn-redraw').off('click');
	$('#btn-redraw').on('click', redrawTopChart);

	$('input[name="cross_items"]:radio').off('change');
	$('input[name="cross_items"]:radio').on('change', redrawCrossChart);

	redrawTopChart();

	function redrawTopChart() {
		let identId = $('#identId')[0].value;

		// 初期表示ではクロス集計を無効にする
		$('input[name="cross_items"]').prop('checked', false);

		// 除外パラメータ取得
		let params = [];
		$('input[name="sum_items"]').each(function() {
			if ($(this).prop('checked') === false) {
				params.push(this.id);
			}
		});

		let max_try = 3;
		let retry = 0;

		$.ajax({
			type : 'GET',
			url : 'data3',
			data : {
				param : params,
				ident_id : identId
			},
			async : true,
			success : function(results) {
				if (results != null) {
					for (let i = 0; i < results.length; i++) {
						drawChart('chart_pie', 'pie', results[i]);
						drawChart('chart_doughnut', 'doughnut', results[i]);
						drawChart('chart_bar', 'bar', results[i]);
						drawChart('chart_line', 'line', results[i]);
					}
				}
			},
			error : function(error) {
				if (retry < max_try) {
					retry++;
					console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']');
					let func = this;
					setTimeout(function() {
						$.ajax(func)
					}, 500);
				} else {
					console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']、[ステータス:'
							+ error.status + ']、[メッセージ:' + error.statusText
							+ ']');
				}
			}
		});
	}

	function redrawCrossChart() {

		let identId = $('#identId')[0].value;

		// 除外パラメータ取得
		let params = [];
		$('input[name="sum_items"]').each(function() {
			if ($(this).prop('checked') === false) {
				params.push(this.id);
			}
		});

		let max_try = 3;
		let retry = 0;

		// クロス集計パラメータ取得
		let cossId = this.id;

		$.ajax({
			type : 'GET',
			url : 'cross',
			data : {
				param : params,
				ident_id : identId,
				cross_id : cossId
			},
			async : true,
			success : function(results) {
				if (results != null) {
					for (let i = 0; i < results.length; i++) {
						drawCrossChart('chart_bar', 'bar', results[i]);
						drawCrossChart('chart_line', 'line', results[i]);
						drawCrossChart('chart_doughnut', 'doughnut', results[i]);
					}
				}
			},
			error : function(error) {
				if (retry < max_try) {
					retry++;
					console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']');
					let func = this;
					setTimeout(function() {
						$.ajax(func)
					}, 500);
				} else {
					console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']、[ステータス:'
							+ error.status + ']、[メッセージ:' + error.statusText
							+ ']');
				}
			}
		});
	}


	/**
	 * 引数にしたがってチャート描画を行う
	 */
	function drawChart(elmId, type, summary) {
		let disp_xAxes = elmId == "chart_bar" ? true : false;
		var datas = [];

		let config = {
			type : type,
			data : {
				labels : summary.labels,
				datasets: [{
					label: summary.title,
					data: summary.datas
				}]
			},
			options : {
				legend : {
					position : "bottom",
					labels : {
						fontSize : 10,
						fontFamily : "メイリオ, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
					}
				},
				plugins : {
					colorschemes : {
						scheme : 'tableau.Tableau20',
					}
				},
				title : {
					display : false,
					fontSize : 20,
					text : summary.title,
					position : "top",
				},
				scales: {
//					xAxes: [
//						{
//							scaleLabel: {
//								display: false,
//							},
//						},
//					],
					yAxes: [
						{
							display: false,
							ticks: {
								min: 0,
							},
						},
					],
				},
			},
			responsive : false
		}
		let grapharea = $("#" + elmId)[0].getContext("2d");
		if (myChart[elmId]) {
			myChart[elmId].destroy();
		}
		myChart[elmId] = new Chart(grapharea, config);
	}

	/**
	 * 引数にしたがってクロスチャート描画を行う
	 */
	function drawCrossChart(elmId, type, summary) {
		let disp_xAxes = elmId == "chart_bar" ? true : false;
		var datas = [];
		for (let i = 0; i < summary.datas.length; i++) {
			let elm = {
				label: summary.data_labels[i],
				data : summary.datas[i]
			}
			datas.push(elm);
		}

		let config = {
			type : type,
			data : {
				labels : summary.labels,
				datasets : datas
			},
			options : {
				legend : {
					position : "bottom",
					labels : {
						fontSize : 10,
						fontFamily : "メイリオ, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
					}
				},
				plugins : {
					colorschemes : {
						scheme : 'tableau.Tableau20',
					}
				},
				title : {
					display : false,
					fontSize : 20,
					text : summary.title,
					position : "top",
				},
				scales: {
					yAxes: [
						{
							ticks: {
								min: 0,
							},
						},
					],
				},
			},
			responsive : false
		}
		let grapharea = $("#" + elmId)[0].getContext("2d");
		if (myChart[elmId]) {
			myChart[elmId].destroy();
		}
		myChart[elmId] = new Chart(grapharea, config);
	}
});