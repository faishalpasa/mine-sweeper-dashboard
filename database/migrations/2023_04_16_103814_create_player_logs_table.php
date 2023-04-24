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
    Schema::create('player_logs', function (Blueprint $table) {
      $table->id();
      $table->integer('player_id');
      $table->integer('level_id');
      $table->text('state');
      $table->integer('score');
      $table->integer('time');
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
    Schema::dropIfExists('player_logs');
  }
};
