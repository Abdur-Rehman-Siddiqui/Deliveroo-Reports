<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;

class LineChartController extends Controller
{

  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;

  }

  public function lineChart()
  {
    $dates = ['0','1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
    $preparedData = [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];
    $currentDate = Carbon::now()->format('Y-m-d H:i:s');
    $dateMonthEarlier = Carbon::now()->subMonth()->format('Y-m-d H:i:s');
    $orders = $this->order->whereBetween('created_at', [$dateMonthEarlier, $currentDate])->get();


    $orderDate = [];
    $totalPrice=0;
    $count=0;
    foreach ($orders as $order) {
      $orderDate[] = $order->created_at->format('d');
      $totalPrice=$totalPrice+$order->total_price;
      $count++;
    }
    $occurrences = array_count_values($orderDate);

    foreach ($occurrences as $index => $occurrence) {
      $preparedData[$index] = $occurrence;
    }

    $data = [
      'labels' => $dates,
      'data' => $preparedData,
    ];
    return view('line-chart', compact('data'));
  }
}
