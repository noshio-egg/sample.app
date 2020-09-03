<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('sales', function (Blueprint $table) {
    		$table->id();
    		$table->string('company_name')->comment('会社名');
    		$table->integer('amount')->comment('販売金額');
    		$table->integer('year')->comment('販売した年');
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
