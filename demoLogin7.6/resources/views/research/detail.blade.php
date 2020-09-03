@extends('layouts.app') @section('content')
<div id="app" class="container p-3">
	<div class="row">
		<div class="col-lg-12">
			<h3>{{ $detail_title }}</h3>
		</div>
		<div class="col-lg-7 detail-top">
			<canvas id="chart_pie" style="width: 100%; min-height: 250px;"></canvas>
		</div>
	</div>

	<input id="identId" type="hidden" value="{{ $identId }}">

	<div class="row cross-chart-area">
		<!--/* 除外条件用チェックボックス */-->
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
			<a class="btn btn-primary" id="btn-redraw" data-groups="{{ $identId }}">redraw</a>
		</div>

		<!--/* クロス集計用ラジオボタン */-->
		<div class="col-lg-3">
			<ul class="list-group list-group-flush">
			@foreach ($groups as $group)
				@if ($identId != $group->def_id)
					@if (empty($group->rows))
					<li class="list-group-item">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="cross_items" id="check_item_{{ $group->def_id }}">
							<label class="form-check-label" for="def_id_{{ $group->def_id }}">{{ $group->title }}</label>
						</div>
					</li>
					@else
						@for ($i = 0; $i < count($group->rows); $i++)
						<li class="list-group-item">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="cross_items" id="check_item_{{ $group->def_id }}_{{ $i }}">
								<label class="form-check-label" for="def_id_{{ $group->def_id }}_{{ $i }}">{{ $group->rows[$i]->title }}</label>
							</div>
						</li>
						@endfor
					@endif
				@endif
			@endforeach
			</ul>
		</div>

		<!--/* グラフ描画領域 */-->
		<div class="col-lg-6">
			<div class="row">
				<div class="col-lg-12 cross-chart">
					<canvas id="chart_bar" style="width: 100%; min-height: 250px;"></canvas>
				</div>
				<div class="col-lg-12 cross-chart">
					<canvas id="chart_doughnut" style="width: 100%; min-height: 250px;"></canvas>
				</div>
				<div class="col-lg-12 cross-chart">
					<canvas id="chart_line" style="width: 100%; min-height: 250px;"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="application/javascript" src="{{ asset('js/mychartdtl.js') }}" defer></script>
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script type="application/javascript" src="https://unpkg.com/chartjs-plugin-colorschemes"></script>
@endsection