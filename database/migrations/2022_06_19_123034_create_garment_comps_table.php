<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGarmentCompsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garment_comps', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('work_order_header_id')->unsigned()->nullable();
            $table->foreign('work_order_header_id')->references('id')->on('work_order_headers');             
            $table->string('garment_comp', 1500)->nullable();
            $table->longtext('fibre_string')->nullable();
            $table->longtext('fibrecontent1')->nullable();
            $table->longtext('fibrecontent2')->nullable();
            $table->longtext('fibrecontent3')->nullable();
            $table->longtext('fibrecontent4')->nullable();
            $table->longtext('fibrecontent5')->nullable();
            $table->longtext('fibrecontent6')->nullable();
            $table->longtext('fibrecontent7')->nullable();
            $table->longtext('fibrecontent8')->nullable();
            $table->string('garment_comp_chinese', 1500)->nullable();
            $table->longtext('fibrecontent1_chinese')->nullable();
            $table->longtext('fibrecontent2_chinese')->nullable();
            $table->longtext('fibrecontent3_chinese')->nullable();
            $table->longtext('fibrecontent4_chinese')->nullable();
            $table->longtext('fibrecontent5_chinese')->nullable();
            $table->longtext('fibrecontent6_chinese')->nullable();
            $table->longtext('fibrecontent7_chinese')->nullable();
            $table->longtext('fibrecontent8_chinese')->nullable();            
            $table->string('garment_compadd_chinese', 1500)->nullable();
            $table->longtext('fibrecontentadd9_chinese')->nullable();
            $table->longtext('fibrecontentadd10_chinese')->nullable();
            $table->string('garment_compadd', 1500)->nullable();
            $table->longtext('fibrecontentadd9')->nullable();
            $table->longtext('fibrecontentadd10')->nullable();
            $table->longtext('fibrecontentadd11')->nullable();
            $table->longtext('fibrecontentadd12')->nullable();
            $table->longtext('fibrecontentadd13')->nullable();
            $table->longtext('fibrecontentadd14')->nullable();
            $table->longtext('fibrecontentadd15')->nullable();
            $table->longtext('fibrecontentadd16')->nullable();
            $table->longtext('fibrecontentadd17')->nullable();
            $table->longtext('fibrecontentadd18')->nullable();
            $table->longtext('fibrecontentadd19')->nullable();
            $table->longtext('fibrecontentadd20')->nullable();
            $table->string('evenmore_garment_compadd_chinese', 1500)->nullable();
            $table->longtext('fibrecontentadd11_chinese')->nullable();
            $table->longtext('fibrecontentadd12_chinese')->nullable();
            $table->longtext('fibrecontentadd13_chinese')->nullable();
            $table->longtext('fibrecontentadd14_chinese')->nullable();
            $table->longtext('fibrecontentadd15_chinese')->nullable();
            $table->longtext('fibrecontentadd16_chinese')->nullable();
            $table->longtext('fibrecontentadd17_chinese')->nullable();
            $table->longtext('fibrecontentadd18_chinese')->nullable();
            $table->longtext('fibrecontentadd19_chinese')->nullable();
            $table->longtext('fibrecontentadd20_chinese')->nullable();
            $table->longtext('instruction_chinese')->nullable();
            $table->longtext('carephrase_string')->nullable();
            $table->longtext('carephrase_string_chinese')->nullable();
            $table->longtext('set2_carephrase_string')->nullable();
            $table->longtext('set2_carephrase_string_chinese')->nullable();
            $table->longtext('evenmore_garment_compadd')->nullable();
            $table->string('MadeIn', 1500)->nullable();
            $table->string('madein_french', 1500)->nullable();
            $table->string('madein_spanish', 1500)->nullable();
            $table->string('instruction', 1500)->nullable();
            $table->string('madein_italian', 1500)->nullable();
            $table->string('madein_chinese', 1500)->nullable();            
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
        Schema::dropIfExists('garment_comps');
    }
}