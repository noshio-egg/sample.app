@extends('layouts.app') @section('content')
<div id="app" class="container p-3">
	<div class="row">
		<div class="col-lg-3">
			@foreach ($groups as $group)
			<p>
				<a class="btn btn-primary" data-toggle="collapse" href="#def_id_{{ $group->def_id }}" role="button" aria-expanded="true" aria-controls="def_id_{{ $group->def_id }}">
					{{ $group->title }}
				</a>
			</p>
			<div class="collapse def-nav-inline" id="def_id_{{ $group->def_id }}">
				@if (empty($group->rows))
				<ul class="list-group list-group-flush">
					@for ($i = 0; $i < count($group->items); $i++)
					<li class="list-group-item">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="sum_items" id="def_id_{{ $group->def_id }}_{{ $i }}" checked>
							<label class="form-check-label" for="def_id_{{ $group->def_id }}_{{ $i }}">{{ $group->items[$i] }}</label>
						</div>
					</li>
					@endfor
				</ul>
				@else
					@for ($i = 0; $i < count($group->rows); $i++)
					<p>
						<a class="btn btn-primary" data-toggle="collapse" href="#def_id_{{ $group->def_id . '-' . $i }}" role="button" aria-expanded="true" aria-controls="def_id_{{ $group->def_id . '-' . $i }}">
							{{ $group->rows[$i]->title }}
						</a>
					</p>
					<div class="collapse" id="def_id_{{ $group->def_id . '-' . $i }}">
						<ul class="list-group list-group-flush">
							<?php for ($n = 0; $n < count($group->rows[$i]->items); $n++) { ?>
							<li class="list-group-item">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="sum_items" id="def_id_{{ $group->def_id }}_{{ $i }}_{{ $n }}" checked>
									<label class="form-check-label" for="def_id_{{ $group->def_id }}_{{ $i }}_{{ $n }}">{{ $group->rows[$i]->items[$n] }}</label>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					@endfor
				@endif
			</div>
			@endforeach
			<a class="btn btn-primary" id="btn-redraw" data-groups="{{ implode(',', $defIds) }}">redraw</a>
		</div>

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
						<canvas id="chart{{ $titles[$i]['id'] }}" width="100%" height="100%"></canvas>
					</td>
					@if ($i == (count($titles) + 1))
					</tr>
					@endif
				@endfor
				</tbody>
			</table>
		@endif
		</div>
	</div>
</div>

<script type="application/javascript" src="{{ asset('js/mychart.js') }}" defer></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script type="application/javascript" src="https://unpkg.com/chartjs-plugin-colorschemes"></script>
<script type="application/javascript">
//  	var ct = new Vue({
//  		el: '#ct',
//  		data: {
//  			chart: null
//  		},
//  		methods: {
//  			hoge: function() {
//  				this.getSales();
//  			},
//  			getSales() {
//  				let param = ['def_id_1264086270_3_0', 'def_id_1264086270_3_1'];
//  				fetch('data2?param[]=def_id_1264086270_3_0&param[]=def_id_1264086270_3_1')
//  					.then(response => response.json())
//  					.then(group => {
//  						// チャートが存在していれば初期化
//  						if(this.chart) {
//  							this.chart.destroy();
//  						}

//  						// グラフ描画
//  						for (let i = 0; i < group.length; i++) {
//  							const ctx = document.getElementById('chart' + i).getContext('2d');
//  							this.chart = new Chart(ctx, {
//  								type: 'pie',
//  								data: {
//  									datasets: [{
//  										data: group[i].datas,
//  									}],
//  									labels: group[i].labels
//  								},
//  								options: {
//  									legend: {			 // 凡例の設定
//  										position: "bottom",	 // 表示位置
//  										labels: {			  // 凡例文字列の設定
//  											fontSize: 10,
//  											fontFamily: "メイリオ, 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
//  										}
//  									},
//  									plugins: {
//  										colorschemes: {
//  											scheme: 'tableau.Tableau20',
//  										}
//  									},
//  									title: {
//  										display: false,
//  										fontSize: 20,
//  										text: group[i].title,
//  										position: "top",
//  									}
//  								}
//  							});
//  						}
//  					});
//  			}
//  		},
//  		// DOM要素が出来てから実行する
//  		mounted() {
//  			this.getSales();
//  		}
//  	});
</script>
@endsection