<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('level_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->tinyInteger('fee_type_id')->nullable();
            $table->string('fee_description')->nullable();
            $table->unsignedBigInteger('fee_id')->nullable();
            $table->unsignedBigInteger('bill_book_id')->nullable();
            $table->string('physical_receipt_no')->nullable();
            $table->date('physical_receipt_date')->nullable();
            $table->unsignedInteger('receipt_id')->nullable();
            $table->tinyInteger('royalty_status');
            $table->string('comments')->nullable();
            $table->tinyInteger('fee_payment_type')->nullable();
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
        Schema::dropIfExists('fee_collections');
    }
}
