<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetDataDetailsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sheet_data_details', function (Blueprint $table) {
			$table->bigInteger('row_id', true, true);
			$table->bigInteger('data_id')
				->unsigned()
				->nullable(false);
			$table->bigInteger('def_id', false, true)->nullable(false);
			$table->string('ident_id', 255);
			$table->string('value', 255)->nullable(true);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			// foreign key.
			$table->foreign('data_id')
				->references('data_id')
				->on('sheet_datas');
			$table->foreign('def_id')
				->references('def_id')
				->on('sheet_defs');
			// index.
			$table->index([
				'data_id',
				'def_id'
			]);
			$table->index([
				'data_id',
				'ident_id',
				'value'
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
		Schema::dropIfExists('sheet_data_details');
	}
}
