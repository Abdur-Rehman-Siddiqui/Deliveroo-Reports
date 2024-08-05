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
    Schema::create('menu_items', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('restaurant_id')->unsigned();
      $table->bigInteger('menu_id')->unsigned();
      $table->bigInteger('item_id')->unsigned();
      $table->foreign('restaurant_id')->references('id')->on('restaurants');
      $table->foreign('item_id')->references('id')->on('items');
      $table->foreign('menu_id')->references('id')->on('menus');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menu_items');
  }
};
