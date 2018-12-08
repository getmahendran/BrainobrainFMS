<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateMastersTable extends Migration
{
    public function up()
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('user_name');
            $table->string('mobile');
            $table->string('email',100);
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('masters');
    }
}