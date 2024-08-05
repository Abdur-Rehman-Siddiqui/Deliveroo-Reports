<?php

namespace App\Http\Controllers\reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class ItemSale extends Controller
{
  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;

  }

  public function index()
  {
    $currentDate = Carbon::now()->format('Y-m-d');
    $dateMonthEarlier = Carbon::now()->subMonth()->format('Y-m-d');
    $orders = $this->order->whereBetween('created_at', [$dateMonthEarlier, $currentDate])->get();


    // Replace this with your actual data retrieval logic
    $data = [
      'labels' => ['1', '2', '3', '4', '5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'],

      'data' => [65, 59, 80, 81, 56],
    ];
    return view('line-chart', compact('data'));
  }
}
