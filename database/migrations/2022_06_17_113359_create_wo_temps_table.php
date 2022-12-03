<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWoTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wo_temps', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('fm_chainid', 30)->nullable();
            $table->string('works_order_no', 30)->nullable();
            $table->string('fm_custname', 30)->nullable(); 
            $table->date('fm_deliverydate')->nullable(); 
            $table->string('fm_productid', 30)->nullable();
            $table->string('ordertype', 20)->nullable();
            $table->string('orderstatus', 100)->nullable();
            $table->string('deliveryaddress', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wo_temps');
    }
}
