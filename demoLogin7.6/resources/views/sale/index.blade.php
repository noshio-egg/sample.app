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
			<div class="col-md-4">
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
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢3</label>
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
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢3</label>
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
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢1</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢2</label>
								</div>
							</li>
							<li>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="check1a" checked>
									<label class="form-check-label" for="check1a">選択肢3</label>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="col-md-8">
				<div class="col-lg-4" style="float: left;">
					<!-- 👇 円グラフを表示するキャンバス -->
					<canvas id="chart0" width="100%" height="100%"></canvas>
					<!-- 👇 年を選択するセレクトボックス -->
					<!-- 				<div class="form-group"> -->
					<!-- 					<label>販売年</label> <select class="form-control" v-model="year" -->
					<!-- 						@change="getSales"> -->
					<!-- 						<option v-for="year in years" :value="year">@{{ year }} 年</option> -->
					<!-- 					</select> -->
					<!-- 				</div> -->
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart1" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart2" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart3" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart4" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart5" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart6" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart7" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart8" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<canvas id="chart9" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<!-- 👇 ドーナツチャートを表示するキャンバス -->
					<canvas id="myPieChart" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<!-- 👇 バーチャートを表示するキャンバス -->
					<canvas id="myBarChart" width="100%" height="100%"></canvas>
				</div>
				<div class="col-lg-4" style="float: left;">
					<!-- 👇 バーチャートを表示するキャンバス -->
					<canvas id="myLineChart" width="100%" height="100%"></canvas>
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
		for (let i = 0; i < 10; i++) {
        new Vue({
            el: '#app',
            data: {
                sales: [],
                year: '{{ date('Y') }}',
                years: [],
                chart: null
            },
            methods: {
                getYears() {

                    // 👇 販売年リストを取得 ・・・ ①
                    fetch('ajax/sales/years')
                        .then(response => response.json())
                        .then(data => this.years = data);

                },
                getSales() {

                    // 👇 販売実績データを取得 ・・・ ②
                    fetch('ajax/sales?year='+ this.year)
                        .then(response => response.json())
                        .then(data => {

                            if(this.chart) { // チャートが存在していれば初期化

                                this.chart.destroy();

                            }

                            // 👇 lodashでデータを加工 ・・・ ③
                            const groupedSales = _.groupBy(data, 'company_name'); // 会社ごとにグループ化
                            const amounts = _.map(groupedSales, companySales => {

                                return _.sumBy(companySales, 'amount'); // 金額合計

                            });
                            const companyNames = _.keys(groupedSales); // 会社名

                            // 👇 円グラフを描画 ・・・ ④
                            const ctx = document.getElementById('chart' + i).getContext('2d');
                            this.chart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    datasets: [{
                                        data: amounts,
                                    }],
                                    labels: companyNames
                                },
                                options: {
                                    onClick: function (e, el) {
                                        alert("key:company_name, value:" + el[0]._model.label);
                                        document.forms.detail.submit();
                                    },
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
                                        display: true,
                                        fontSize: 30,
                                        text: '質問' + (i + 1),
                                        position: "top",
                                    },
                                    tooltips: {
                                        callbacks: {
                                            label(tooltipItem, data) {

                                                const datasetIndex = tooltipItem.datasetIndex;
                                                const index = tooltipItem.index;
                                                const amount = data.datasets[datasetIndex].data[index];
                                                const amountText = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                                const company = data.labels[index];
                                                return ' '+ company +' '+amountText +' 円';

                                            }
                                        }
                                    }
                                }
                            });

                        });

                }
            },
            mounted() {

                this.getYears();
                this.getSales();

            }
        });
		}
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
      }]
    },
    options: {
      onClick: function (e, el) {
          alert("key:company_name, value:" + el[0]._model.label);
      },
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
        display: true,
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
      labels: ["A型", "O型", "B型", "AB型"],
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
        onClick: function (e, el) {
            alert("key:company_name, value:" + el[0]._model.label);
        },
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
        display: true,
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
      labels: ["A型", "O型", "B型", "AB型"],
      datasets: [{
          label: "男性",
          data: [38, 31, 21, 10]
      },
      {
          label: "女性",
          data: [31, 21, 10, 38]
      }]
    },
    options: {
        onClick: function (e, el) {
            alert("key:company_name, value:" + el[0]._model.label);
        },
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
        display: true,
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