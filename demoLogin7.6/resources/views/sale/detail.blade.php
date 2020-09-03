<html>
<head>
<!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
<link
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
	rel="stylesheet">
<script defer
	src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
</head>
<body>
	<div id="app" class="container p-3">
		<div class="row">
			<div class="col-md-12">
				<h1 style="width: 100%; padding: 1.5em .5em;">当クラブをどこで知りましたか？</h1>

				<div class="col-md-6">
					<canvas id="myAllChart" width="100%" height="50%"></canvas>
				</div>
			</div>
			<div class="col-md-3">
				<h3 style="width: 100%; text-align: center;">条件指定</h3>
				<ul class="nav flex-column">
					<li class="nav-item">
						<div class="d-flex align-items-center">
							<a href="#" class="nav-link"> 質問1 </a>
							<button class="btn btn-link btn-sm" data-target="#collapse-menu1"
								data-toggle="collapse">
								<i class="fas fa-angle-down"></i>
							</button>
						</div>
						<ul id="collapse-menu1" class="collapse list-unstyled pl-3">
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢3</label>
								</div>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<div class="d-flex align-items-center">
							<a href="#" class="nav-link"> 質問2 </a>
							<button class="btn btn-link btn-sm" data-target="#collapse-menu2"
								data-toggle="collapse">
								<i class="fas fa-angle-down"></i>
							</button>
						</div>
						<ul id="collapse-menu2" class="collapse list-unstyled pl-3">
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢3</label>
								</div>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<div class="d-flex align-items-center">
							<a href="#" class="nav-link"> 質問3 </a>
							<button class="btn btn-link btn-sm" data-target="#collapse-menu3"
								data-toggle="collapse">
								<i class="fas fa-angle-down"></i>
							</button>
						</div>
						<ul id="collapse-menu3" class="collapse list-unstyled pl-3">
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a"
										checked> <label class="form-check-label" for="check1a">選択肢3</label>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="col-md-3" style="float: left;">
				<label class="control-label"><h3>追加質問一覧</h3></label>
				<ul class="list-group list-group-vertical col-md-12"
					style="padding-bottom: 5em;">
					<li class="list-group-item" style="border: none;"><div
							class="custom-control custom-radio">
							<input type="radio" name="add-question"
								class="custom-control-input" id="custom-radio-1"> <label
								class="custom-control-label" for="custom-radio-1"
								style="min-width: 50">年代</label>
						</div></li>
					<li class="list-group-item" style="border: none;"><div
							class="custom-control custom-radio">
							<input type="radio" name="add-question"
								class="custom-control-input" id="custom-radio-2"> <label
								class="custom-control-label" for="custom-radio-2"
								style="min-width: 50">性別</label>
						</div></li>
					<li class="list-group-item" style="border: none;"><div
							class="custom-control custom-radio">
							<input type="radio" name="add-question"
								class="custom-control-input" id="custom-radio-3"> <label
								class="custom-control-label" for="custom-radio-3"
								style="min-width: 50">当クラブをどこで知りましたか</label>
						</div></li>
					<li class="list-group-item" style="border: none;"><div
							class="custom-control custom-radio">
							<input type="radio" name="add-question"
								class="custom-control-input" id="custom-radio-4"> <label
								class="custom-control-label" for="custom-radio-4"
								style="min-width: 50">何に興味がありますか</label>
						</div></li>
					<li class="list-group-item" style="border: none;"><div
							class="custom-control custom-radio">
							<input type="radio" name="add-question"
								class="custom-control-input" id="custom-radio-5"> <label
								class="custom-control-label" for="custom-radio-5"
								style="min-width: 50">入会の意思はございますか？</label>
						</div></li>
				</ul>
			</div>

			<div class="col-md-6">
				<div class="col-md-12" style="float: left;">
					<!-- 👇 ドーナツチャートを表示するキャンバス -->
					<canvas id="myPieChart" width="100%" height="50%"></canvas>
				</div>
				<div class="col-md-12" style="float: left;">
					<!-- 👇 バーチャートを表示するキャンバス -->
					<canvas id="myBarChart" width="100%" height="50%"></canvas>
				</div>
				<div class="col-md-12" style="float: left;">
					<!-- 👇 バーチャートを表示するキャンバス -->
					<canvas id="myLineChart" width="100%" height="50%"></canvas>
				</div>

			</div>

		</div>
		<form id="detail" action="login" method="get"></form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
	<script
		src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
	<script src="https://unpkg.com/chartjs-plugin-colorschemes"></script>

	<script>
  var ctx = document.getElementById("myAllChart");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["駅の看板", "インターネット", "クーポンサイト", "電車内広告"],
      datasets: [{
          data: [71, 67, 58, 69]
      }]
    },
    options: {
      legend: {             // 凡例の設定
          position: "right",     // 表示位置
          labels: {              // 凡例文字列の設定
              fontSize: 10,
          }
      },
      plugins: {
          colorschemes: {
              scheme: 'tableau.Tableau20',
          }
      },
      title: {
        display: false,
        fontSize: 30,
        text: '当クラブをどこでお知りになったか教えてください',
        position: "bottom",
      }
    }
  });
  </script>

	<script>
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ["駅の看板", "インターネット", "クーポンサイト", "電車内広告"],
      datasets: [{
          label: "男性",
          data: [38, 31, 21, 10]
      },
      {
          label: "女性",
          data: [31, 21, 10, 38]
      },
      {
          label: "その他",
          data: [2, 15, 27, 21]
      }]
    },
    options: {
      legend: {             // 凡例の設定
          position: "right",     // 表示位置
          labels: {              // 凡例文字列の設定
              fontSize: 10,
          }
      },
      plugins: {
          colorschemes: {
              scheme: 'tableau.Tableau20',
          }
      },
      title: {
        display: false,
        fontSize: 30,
        text: '当クラブをどこでお知りになったか教えてください',
        position: "bottom",
      }
    }
  });
  </script>
	<script>
  var ctx = document.getElementById("myBarChart");
  var myPieChart = new Chart(ctx, {
    type: 'bar',
    data: {
    	labels: ["駅の看板", "インターネット", "クーポンサイト", "電車内広告"],
      datasets: [{
          label: "男性",
          data: [38, 31, 21, 10]
      },
      {
          label: "女性",
          data: [31, 21, 10, 38]
      },
      {
          label: "その他",
          data: [2, 15, 27, 21]
      }]
    },
    options: {
        legend: {             // 凡例の設定
        position: "right",     // 表示位置
        labels: {              // 凡例文字列の設定
            fontSize: 10,
        }
      },
      plugins: {
          colorschemes: {
              scheme: 'tableau.Tableau20',
          }
      },
      title: {
        display: false,
        fontSize: 30,
        text: '血液型 割合',
        position: "bottom",
      },
      scales: {
          yAxes: [
              {
                  ticks: {
                      beginAtZero: true,
                      min: 0,
                      max: 40
                  }
              }
          ]
      }
    }
  });
  </script>
	<script>
  var ctx = document.getElementById("myLineChart");
  var myPieChart = new Chart(ctx, {
    type: 'line',
    data: {
    	labels: ["駅の看板", "インターネット", "クーポンサイト", "電車内広告"],
      datasets: [{
          label: "男性",
          data: [38, 31, 21, 10]
      },
      {
          label: "女性",
          data: [31, 21, 10, 38]
      },
      {
          label: "その他",
          data: [2, 15, 27, 21]
      }]
    },
    options: {
        legend: {             // 凡例の設定
            position: "right",     // 表示位置
        labels: {              // 凡例文字列の設定
            fontSize: 10,
        }
      },
      plugins: {
          colorschemes: {
              scheme: 'tableau.Tableau20',
          }
      },
      title: {
        display: false,
        fontSize: 30,
        text: '血液型 割合',
        position: "bottom",
      },
      scales: {
          yAxes: [
              {
                  ticks: {
                      beginAtZero: true,
                      min: 0,
                      max: 40
                  }
              }
          ]
      }
    }
  });
  </script>
</body>
</html>