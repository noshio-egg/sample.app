<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchDefTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_def', function (Blueprint $table) {
        	$table->bigInteger('forms_id', false, true)->primary();
            $table->bigInteger('research_id')->unsigned()->nullable(false);
            $table->smallInteger('index')->unsigned()->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('data_type', 64)->nullable(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            // foreign key.
            $table->foreign('research_id')->references('id')->on('research');
            // index.
            $table->index(['research_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_def');
    }
}
