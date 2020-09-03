<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetDefAttrsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sheet_def_attrs', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('sheet_id', false, true)->nullable(false);
			$table->bigInteger('def_id', false, true)->nullable(false);
			$table->string('key', 64)->nullable(false);
			$table->string('value')->nullable(false);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			// foreign key.
			$table->foreign('sheet_id')
				->references('sheet_id')
				->on('sheets');
			$table->foreign('def_id')
				->references('def_id')
				->on('sheet_defs');
			// index.
			$table->index([
				'sheet_id',
				'def_id'
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
		Schema::dropIfExists('sheet_def_attrs');
	}
}
