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
    Schema::table('players', function (Blueprint $table) {
      $table->string('is_first_time_pin')->nullable()->after('pin');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('players', function (Blueprint $table) {
      $table->dropColumn('is_first_time_pin');
    });
  }
};
