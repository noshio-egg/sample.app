<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SalesTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$companies = [
			'株式会社 恵比寿',
			'株式会社 大黒天',
			'株式会社 毘沙門天',
			'株式会社 弁財天',
			'株式会社 福禄寿',
			'株式会社 寿老人',
			'株式会社 布袋'
		];

		for ($i = 0; $i < 100; $i ++) {

			$sale = new \App\Sale();
			$sale->company_name = Arr::random($companies);
			$sale->amount = rand(1000, 10000) * 10;
			$sale->year = rand(2018, 2020);
			$sale->save();
		}
	}
}
