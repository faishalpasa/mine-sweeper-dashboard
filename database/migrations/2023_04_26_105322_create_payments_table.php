<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();
      $table->integer('player_id');
      $table->string('channel');
      $table->string('amount');
      $table->string('currency')->nullable();
      $table->string('reference_id')->nullable();
      $table->string('invoice_no')->nullable();
      $table->string('status');
      $table->string('msisdn');
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
    Schema::dropIfExists('payments');
  }
};
