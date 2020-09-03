<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchDefAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_def_attr', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('research_id', false, true)->nullable(false);
            $table->bigInteger('forms_id', false, true)->nullable(false);
            $table->string('key', 64)->nullable(false);
            $table->string('value')->nullable(false);
            $table->timestamps();
            // foreign key.
            $table->foreign('research_id')->references('id')->on('research');
            $table->foreign('forms_id')->references('forms_id')->on('research_def');
            // index.
            $table->index(['research_id', 'forms_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('research_def_attr');
    }
}
