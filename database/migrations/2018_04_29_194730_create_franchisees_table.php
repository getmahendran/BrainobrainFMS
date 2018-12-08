<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFranchiseesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchisees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('center_code');
            $table->string('name');
            $table->string('gender');
            $table->string('path')->nullable();
            $table->date('dob');
            $table->string('father_name');
            $table->text('permanent_address');
            $table->string('contact_no1');
            $table->string('contact_no2')->nullable();
            $table->string('occupation')->nullable();
            $table->string('qualification');
            $table->string('email',100);
            $table->boolean('married');
            $table->string('spouse_name')->nullable();
            $table->date('spouse_dob')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->string('spouse_qualification')->nullable();
            $table->date('wedding_anniversary')->nullable();
            $table->string('child1_name')->nullable();
            $table->date('child1_dob')->nullable();
            $table->string('child2_name')->nullable();
            $table->date('child2_dob')->nullable();
            $table->string('child3_name')->nullable();
            $table->date('child3_dob')->nullable();
            $table->unsignedInteger('family_income')->nullable();
            $table->text('languages_known');
            $table->text('hobbies')->nullable();
            $table->text('special_at')->nullable();
            $table->text('past_experience')->nullable();
            $table->string('franchisee_name');
            $table->string('franchisee_address');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('franchisees');
    }
}
