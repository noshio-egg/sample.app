<!-- 検証用。動かないです。 -->
<template>
<div class="col-lg-9">
	@if (isset($titles))
		<table class="table">
			<tbody>
			@for ($i = 0; $i < count($titles); $i++)
				@if ($i == 0)
				<tr class="d-flex">
				@endif
				@if ($i != 0 && $i % 3 == 0)
				</tr><tr class="d-flex">
				@endif
				<td class="col-4">
					<a class="specific-link" id="btn-show-detail" data-key="{{ $titles[$i]['id'] }}"><h5>{{ $titles[$i]['title'] }}</h5></a>
					<canvas id="chart{{ $i }}" width="100%" height="100%"></canvas>
				</td>
				@if ($i == (count($titles) + 1))
				</tr>
				@endif
			@endfor
			</tbody>
		</table>
	@endif
</div>
</template>

<script>
	export default {
		name: 'all-chart-component',
		data: {
			chart: null
		},
		methods: {
			hoge: function() {
				this.getSales();
			},
			getSales() {
				let param = ['def_id_1264086270_3_0', 'def_id_1264086270_3_1'];
				fetch('data2?param[]=def_id_1264086270_3_0&param[]=def_id_1264086270_3_1')
					.then(response => response.json())
					.then(group => {
						// チャートが存在していれば初期化
						if(this.chart) {
							this.chart.destroy();
						}

						// グラフ描画
						for (let i = 0; i < group.length; i++) {
							const ctx = document.getElementById('chart' + i).getContext('2d');
							this.chart = new Chart(ctx, {
								type: 'pie',
								data: {
									datasets: [{
										data: group[i].datas,
									}],
									labels: group[i].labels
								},
								options: {
									legend: {			 // 凡例の設定
										position: "bottom",	 // 表示位置
										labels: {			  // 凡例文字列の設定
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
										text: group[i].title,
										position: "top",
									}
								}
							});
						}
					});
			}
		},
		// DOM要素が出来てから実行する
		mounted() {
			this.getSales();
		}
	};
</script>