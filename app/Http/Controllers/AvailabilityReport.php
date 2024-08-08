<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\OrderHelper;
use App\Charts\Report;
use DateTime;

class AvailabilityReport extends Controller
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
   */
  public function index(Request $request)
  {
    if ($request->get('fromDate') && $request->get('toDate')) {
      $fromDate = new DateTime($request->get('fromDate'));
      $fromDate = $fromDate->format('Y-m-d H:i:s');
      $toDate = new DateTime($request->get('toDate'));
      $toDate = $toDate->format('Y-m-d H:i:s ');
    } else {
      $toDate = Carbon::now()->format('Y-m-d H:i:s');
      $fromDate = Carbon::now()->subWeek()->format('Y-m-d H:i:s');
    }
    $orderValueRejectedOrders = 0;
    //get Total Orders
    $totalOrders = $this->orderHelper->getOrdersInTimeFrame($fromDate, $toDate);
    //get Rejected Orders
    $rejectedOrders = $this->orderHelper->getOrdersInTimeFrame($fromDate, $toDate, $this->orderHelper::ORDERS_STATUS_REJECTED);
    //get Delivered Orders
    $deliveredOrders = $this->orderHelper->getOrdersInTimeFrame($fromDate, $toDate, $this->orderHelper::ORDER_STATUS_DELIVERED);
    $totalRejectedOrdersCount = count($rejectedOrders);
    //Rejected Orders Percentage = (Rejected Orders Count / Total Orders) * 100
    $ordersRejectedPercentage = $this->orderHelper->calculatePercentage($totalRejectedOrdersCount, count($totalOrders));
    //Open Rate = (Delivered Orders / Total Orders) * 100
    $openRate = $this->orderHelper->calculatePercentage(count($deliveredOrders), count($totalOrders));
    // Order Rejected Value = total sale price of rejected orders / count of rejected orders
    if(count($rejectedOrders)>0)
    {
      $orderValueRejectedOrders = $this->orderHelper->calculateTotalPrice($rejectedOrders) / count($rejectedOrders);
    }


    $chart = new Report;
    $chart->labels(['Open Rate', 'Total Rejected Orders', 'Percentage of Rejected Orders', 'Order Value of Rejected Orders']);
    $chart->dataset('', 'bar', [$openRate, $totalRejectedOrdersCount, $ordersRejectedPercentage, $orderValueRejectedOrders])->options([
      'backgroundColor' => '#696cff',
    ]);

    return view('content.reports.availability', compact('chart', 'totalRejectedOrdersCount', 'ordersRejectedPercentage', 'openRate', 'orderValueRejectedOrders'));
  }
}
