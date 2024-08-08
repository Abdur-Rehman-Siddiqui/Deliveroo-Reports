<?php

namespace App\Http\Controllers;

use App\Charts\Report;
use App\Helpers\OrderHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;

class PerformanceReport extends Controller
{

  /**
   * @var OrderHelper
   */
  protected $orderHelper;

  /**
   * @param OrderHelper $orderHelper
   */
  public function __construct(OrderHelper $orderHelper)
  {
    $this->orderHelper = $orderHelper;
  }

  /**
   * @param Request $request
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
   * @throws \Exception
   */
  public function index(Request $request)
  {
    $toDate = Carbon::now()->format('Y-m-d H:i:s');
    $fromDate = Carbon::now()->subWeek()->format('Y-m-d H:i:s');
    if ($request->get('fromDate') && $request->get('toDate')) {
      $fromDate = new DateTime($request->get('fromDate'));
      $fromDate = $fromDate->format('Y-m-d H:i:s');
      $toDate = new DateTime($request->get('toDate'));
      $toDate = $toDate->format('Y-m-d H:i:s ');
    }
    //get Total Orders Delivered
    $orders = $this->orderHelper->getOrdersInTimeFrame($fromDate, $toDate, $this->orderHelper::ORDER_STATUS_DELIVERED);
    $ordersDelivered = count($orders);
    // Gross Sales = sum of total prices of delivered orders
    $grossSales = $this->orderHelper->calculateTotalPrice($orders);
    // Average Order Value is gross sales / delivered orders count
    $averageOrderValue = $grossSales / $ordersDelivered;

    $chart = new Report;
    $chart->labels(['Gross Sales (GBP)', 'Orders Delivered', 'Average Order Value (AOV) (GBP)']);
    $chart->dataset('Gross Sales', 'bar', [$grossSales, $ordersDelivered, $averageOrderValue])->options([
      'backgroundColor' => '#696cff',
    ]);
    return view('content.reports.perfomance', compact('chart', 'grossSales', 'ordersDelivered', 'averageOrderValue'));
  }
}
