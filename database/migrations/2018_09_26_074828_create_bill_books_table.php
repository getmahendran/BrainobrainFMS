<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('franchisee_id');
            $table->unsignedInteger('fee_type_id');
            $table->unsignedInteger('from');
            $table->unsignedInteger('till');
            $table->unsignedInteger('wasted_count')->nullable();
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
        Schema::dropIfExists('bill_books');
    }
}
