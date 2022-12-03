<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemLabelReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_label_references', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('label_reference_id')->unsigned()->nullable();
            $table->foreign('label_reference_id')->references('id')->on('label_references');
            $table->Integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items');             
            $table->float('unit_price')->nullable();
            $table->float('quantity')->nullable();
            $table->float('unit_value')->nullable();                               
            $table->Integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->Integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->Integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
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
        Schema::dropIfExists('item_label_references');
    }
}
