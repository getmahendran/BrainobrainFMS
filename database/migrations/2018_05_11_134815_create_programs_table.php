<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('program_name');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
        DB::table('programs')->insert(
            array([
                'program_name'=>'Littlebobs',
                'status'      =>1
            ],['program_name'=>'Brainobrain','status'      =>1]
            )
        );
    }
    //Insert some stuff


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
