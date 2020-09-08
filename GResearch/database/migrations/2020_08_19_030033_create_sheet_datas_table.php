<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSheetDatasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sheet_datas', function (Blueprint $table) {
			$table->bigInteger('data_id', true, true);
			$table->bigInteger('sheet_id')
				->unsigned()
				->nullable(false);
			$table->timestamp('answer_at', 3)->nullable(true);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			// foreign key.
			$table->foreign('sheet_id')
				->references('sheet_id')
				->on('sheets');
			// index.
			$table->index([
				'sheet_id'
			]);
			$table->index([
				'sheet_id',
				'answer_at'
			]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sheet_datas');
	}
}
