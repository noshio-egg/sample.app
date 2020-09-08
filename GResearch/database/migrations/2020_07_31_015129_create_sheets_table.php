<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSheetsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sheets', function (Blueprint $table) {
			$table->id('sheet_id')->comment('シートID');
			$table->string('sheet_nm')
				->comment('シート名')
				->nullable(false);
			$table->text('form_id')
				->comment('GoogleFormのユニークID')
				->nullable(false);
			$table->text('def_url')
				->comment('シート定義取得URL')
				->nullable(false);
			$table->text('data_url')
				->comment('シートデータ取得URL')
				->nullable(false);
			$table->text('access_key')
				->comment('アクセスKEY')
				->nullable(false);
			$table->boolean('enabled')
				->default(true)
				->comment('有効フラグ')
				->nullable(false);
			$table->dateTime('start_time')
				->comment('開始日時')
				->nullable(false);
			$table->dateTime('end_time')
				->comment('終了日時')
				->nullable(true);
			$table->timestamp('created_at')
				->default(DB::raw('CURRENT_TIMESTAMP'))
				->comment('作成日時');
			$table->timestamp('updated_at')
				->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
				->comment('最終更新日時');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sheets');
	}
}
