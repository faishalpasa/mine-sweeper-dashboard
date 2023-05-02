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
    Schema::table('prizes', function (Blueprint $table) {
      $table->integer('period_id')->nullable()->after('period');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('prizes', function (Blueprint $table) {
      $table->dropColumn('period_id');
    });
  }
};
