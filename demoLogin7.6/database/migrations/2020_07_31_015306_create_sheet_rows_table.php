<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSheetRowsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sheet_rows', function (Blueprint $table) {
			$table->bigInteger('row_id', true, true);
			$table->timestamp('inputed_at')->nullable(true);
			$table->bigInteger('sheet_id')
				->unsigned()
				->nullable(false);
			$table->text('data')->nullable(false);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			// foreign key.
			$table->foreign('sheet_id')
				->references('sheet_id')
				->on('sheets');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sheet_rows');
	}
}
