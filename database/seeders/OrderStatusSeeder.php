<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $statuses = [
      'placed',
      'rejected',
      'accepted',
      'confirmed',
      'canceled',
      'pending',
      'delivered',
      'in_kitchen',
      'ready_for_collection',
      'ready_for_collection_soon',
      'collected',
    ];

    foreach ($statuses as $status) {
      DB::table('order_status')->insert([
        'name' => $status
      ]);
    }
  }
}
