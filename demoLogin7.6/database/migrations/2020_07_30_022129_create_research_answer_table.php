<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchAnswerTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('research_answer', function (Blueprint $table) {
			$table->bigInteger('answer_id', true, true);
			$table->timestamp('answered_at')->nullable(true);
			$table->bigInteger('research_id')
				->unsigned()
				->nullable(false);
			$table->text('data')->nullable(false);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			// foreign key.
			$table->foreign('research_id')
				->references('id')
				->on('research');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('research_answer');
	}
}
