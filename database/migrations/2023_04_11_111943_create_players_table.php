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
    Schema::create('players', function (Blueprint $table) {
      $table->id();
      $table->string('name')->nullable();
      $table->string('email')->unique()->nullable();
      $table->string('msisdn')->unique();
      $table->integer('status');
      $table->integer('coin');
      $table->string('pin');
      $table->string('token');
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
    Schema::dropIfExists('players');
  }
};
