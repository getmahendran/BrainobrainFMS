<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('dob');
            $table->string('gender');
            $table->string('path')->nullable();
            $table->string('school_name');
            $table->string('standard');
            $table->string('father_name');
            $table->string('father_occupation')->nullable();
            $table->text('office_address')->nullable();
            $table->unsignedBigInteger('monthly_income')->nullable();
            $table->string('mother_name');
            $table->string('mother_occupation')->nullable();
            $table->text('residence_address');
            $table->unsignedBigInteger('father_mobile');
            $table->unsignedBigInteger('mother_mobile')->nullable();
            $table->string('father_email');
            $table->string('mother_email')->nullable();
            $table->string('sibling_1_name')->nullable();
            $table->date('sibling_1_dob')->nullable();
            $table->string('sibling_2_name')->nullable();
            $table->date('sibling_2_dob')->nullable();
            $table->unsignedInteger('level_id')->nullable();
            $table->text('about_child');
            $table->tinyInteger('source');
            $table->string('comments')->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('students');
    }
}
