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
    Schema::create('order_items', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('restaurant_id')->unsigned();
      $table->bigInteger('order_id')->unsigned();
      $table->string('pos_item_id')->nullable();
      $table->double('unit_price');
      $table->double('menu_unit_price');
      $table->integer('quantity');
      $table->double('cost_per_unit');
      $table->string('name')->nullable();
      $table->double('discount_amount');
      $table->foreign('restaurant_id')->references('id')->on('restaurants');
      $table->foreign('order_id')->references('id')->on('orders');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_items');
  }
};
