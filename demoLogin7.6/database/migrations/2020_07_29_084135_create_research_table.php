<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research', function (Blueprint $table) {
            $table->id();
            $table->string('research_nm')->comment('アンケート名')->nullable(false);
            $table->text('def_url')->comment('定義URL')->nullable(false);
            $table->text('data_url')->comment('データURL')->nullable(false);
            $table->text('access_key')->comment('アクセスKEY')->nullable(false);
            $table->boolean('enabled')->default(true)->comment('true:有効、false:無効')->nullable(false);
            $table->dateTime('start_time')->comment('開始日時')->nullable(false);
            $table->dateTime('end_time')->comment('終了日時')->nullable(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research');
    }
}
