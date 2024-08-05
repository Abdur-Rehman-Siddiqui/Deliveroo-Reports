<?php

namespace App\Http\Controllers\reports;

use App\Charts\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class Perfomance extends Controller
{
  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;

  }

  public function perfomance()
  {
    $currentDate = Carbon::now()->format('Y-m-d');
    $dateWeekEarlier = Carbon::now()->subWeek()->format('Y-m-d');
    $orders = $this->order->whereBetween('created_at', [$dateWeekEarlier, $currentDate])->get();
    $ordersDelivered = 0;
    $grossSales = 0;
    foreach ($orders as $order) {
      $grossSales = $grossSales + $order->total_price;
      $ordersDelivered++;
    }
    $aov = $grossSales / $ordersDelivered;
    $chart = new Report;
    $chart->labels(['Gross Sales (GBP)', 'Orders Delivered', 'Average Order Value (AOV) (GBP)']);
    $chart->dataset('Gross Sales', 'bar', [$grossSales, $ordersDelivered, $aov])->options([
      'backgroundColor' => '#696cff',
    ]);;
    return view('content.reports.perfomance', compact('chart', 'grossSales', 'ordersDelivered', 'aov'));
  }
}
