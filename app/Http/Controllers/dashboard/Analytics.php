<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Charts\Report;

class Analytics extends Controller
{
  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;

  }
  public function index()
  {
//    $dates = ['0','1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
//    $preparedData = [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null];
//    $currentDate = Carbon::now()->format('Y-m-d H:i:s');
//    $dateMonthEarlier = Carbon::now()->subMonth()->format('Y-m-d H:i:s');
//    $orders = $this->order->whereBetween('created_at', [$dateMonthEarlier, $currentDate])->get();
//
//
//    $orderDate = [];
//    foreach ($orders as $order) {
//      $orderDate[] = $order->created_at->format('d');
//    }
//    $occurrences = array_count_values($orderDate);
//
//    foreach ($occurrences as $index => $occurrence) {
//      $preparedData[$index] = $occurrence;
//    }
//
//    $data = [
//      'labels' => $dates,
//      'data' => $preparedData,
//    ];
//    return view('content.dashboard.dashboards-analytics', compact('data'));

    $chart= new Report;
    $chart->labels(['One','Two','Three','Four']);
    $chart->dataset('my datser','line',[1,2,3,4]);
    return view('content.dashboard.dashboards-analytics',compact('chart'));
  }
}
