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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->string('deliveroo_order_id');
      $table->string('order_number');
      $table->string('order_date')->nullable();
      $table->bigInteger('restaurant_id')->unsigned();
      $table->bigInteger('status_id')->unsigned();
      $table->double('subtotal');
      $table->double('total_price');
      $table->double('delivery_fee');
      $table->double('restaurant_tip')->nullable();
      $table->double('surcharge');
      $table->double('offer_discount')->nullable();
      $table->string('customer_loyalty')->nullable();
      $table->string('prepare_for');
      $table->timestamps();
      $table->foreign('restaurant_id')->references('id')->on('restaurants');
      $table->foreign('status_id')->references('id')->on('order_status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
