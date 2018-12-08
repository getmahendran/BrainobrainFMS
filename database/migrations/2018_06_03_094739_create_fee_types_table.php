<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        //Insert some stuff
        DB::table('fee_types')->insert(
            array([
                'name'      => 'Admission Fee',
                'status'    =>  1
            ],[
                'name' => 'Monthly Fee',
                'status'    =>  1
            ],[
                'name'=>'Exam Fee',
                'status'    =>  1
            ])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_types');
    }
}
