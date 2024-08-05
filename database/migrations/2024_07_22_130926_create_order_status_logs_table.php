<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('order_status_logs', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('order_id')->unsigned();
      $table->string('at');
      $table->bigInteger('status_id')->unsigned();
      $table->timestamp('created_at');
      $table->foreign('status_id')->references('id')->on('order_status');
      $table->foreign('order_id')->references('id')->on('orders');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_status_logs');
  }
};
