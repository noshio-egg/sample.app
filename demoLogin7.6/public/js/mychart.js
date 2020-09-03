$(function() {

	window.myChart = [];
	$('#btn-redraw').off('click');
	$('#btn-redraw').on('click', generateChart2);

	/**
	 * sheet_rows テーブルからのデータ取得
	 * 処理速度が遅いためにこの方針は使用できない
	 */
	function generateChart(event) {

		let groups = this.dataset.groups.split(',');
		let defId = '1264086270';

		let elmIndex = 0;
		groups.forEach(group => {

			$.ajax({
			    type: 'GET',
			    url: 'data2',
			    data: {
			    	param: [
			    		'def_id_1264086270_3_0',
			    		'def_id_1264086270_3_1'
			    	],
			    	def_id: group
			    },
			    async : true,
			    success: function(results) {
			        if(results != null) {
			        	for (let i = 0; i < results.length; i++) {
			        		drawChart('pie', results[i]);
			        	}
			        }
			    }
			});
		});
	}

	/**
	 * sheet_datas テーブルからのデータ取得
	 * レコード数は30倍程度に大きくなるが、処理速度は格段に速いためこちらを採用する
	 */
	function generateChart2(event) {

		let groups = this.dataset.groups.split(',');
		let params = [];
		$('input[name="sum_items"]').each(function() {
			if ($(this).prop('checked') === false) {
				params.push(this.id);
			}
		});

		let max_try = 3;
		let retry = 0;

		groups.forEach(group => {
			$.ajax({
			    type: 'GET',
			    url: 'data3',
			    data: {
			    	param: params,
			    	ident_id: group
			    },
			    async : true,
			    success: function(results) {
			        if(results != null) {
			        	for (let i = 0; i < results.length; i++) {
			        		drawChart('pie', results[i]);
			        	}
			        }
			    },
			    error: function(error) {
			    	if (retry < max_try) {
			    		retry++;
			    		console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']');
			    		let func = this;
			    		setTimeout( function() { $.ajax(func) }, 500 );
			    	} else {
			    		console.log('集計データ取得に失敗しました。[試行回数:' + retry + ']、[ステータス:' + error.status + ']、[メッセージ:' + error.statusText + ']');
			    	}
			    }
			});
		});
	}


	/**
	 * 引数にしたがってチャート描画を行う
	 */
	function drawChart(type, summary) {
		let config = {
			type: type,
			data: {
				labels: summary.labels,
				datasets: [{
					data: summary.datas
				}]
			},
			options: {
				legend: {
					position: "bottom",
					labels: {
						fontSize: 10,
						fontFamily: "メイリオ, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
					}
				},
				plugins: {
					colorschemes: {
						scheme: 'tableau.Tableau20',
					}
				},
				title: {
					display: false,
					fontSize: 20,
					text: summary.title,
					position: "top",
				}
			},
			responsive : true
		}
		let grapharea = $("#chart" + summary.identId)[0].getContext("2d");
		if (myChart[summary.identId]) {
			myChart[summary.identId].destroy();
		}
		myChart[summary.identId] = new Chart(grapharea, config);
	}

		// let group = getAllChartData('1264086270');

//		let defId = '1264086270';
//		async() => {
//			var promiss = await fetch('data2?param[]=def_id_1264086270_3_0&param[]=def_id_1264086270_3_1&def_id=' + defId);
//			var content = await promiss.text();
//			console.log(coantent);
//		}

//		async() => {
//			await (await fetch('data2?param[]=def_id_1264086270_3_0&param[]=def_id_1264086270_3_1&def_id=' + defId))
//			.then(response => response.json())
//			.then(group => {
//				let config = {
//					type: 'pie',
//					data: {
//						labels: group[0].labels,
//						datasets: [{
//							data: group[0].datas
//						}]
//					},
//					options: {
//						legend: {
//							position: "bottom",
//							labels: {
//								fontSize: 10,
//								fontFamily: "メイリオ, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
//							}
//						},
//						plugins: {
//							colorschemes: {
//								scheme: 'tableau.Tableau20',
//							}
//						},
//						title: {
//							display: false,
//							fontSize: 20,
//							text: group[0].title,
//							position: "top",
//						}
//					},
//					responsive : true
//				}
//				let context = $("#chart0");
//				if (context) {
//					context.destroy();
//				}
//
//				let chart = new Chart(context, config);
//			});
//		}
//	}

});