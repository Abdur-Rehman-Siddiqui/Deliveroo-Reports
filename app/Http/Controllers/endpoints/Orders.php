<?php

namespace App\Http\Controllers\endpoints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Helpers\OrderHelper;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use Mockery\Exception;

class Orders extends Controller
{
  const ORDER_NEW_EVENT = 'order.new';
  const ORDER_STATUS_UPDATE_EVENT = 'order.status_update';

  /**
   * @var Order
   */
  protected $order;
  /**
   * @var OrderStatusLog
   */
  protected $orderStatusLog;

  protected $orderItem;
  /**
   * @var OrderHelper
   */
  protected $orderHelper;

  /**
   * @param Order $order
   * @param OrderHelper $orderHelper
   * @param OrderStatusLog $orderStatusLog
   */
  public function __construct(Order $order, OrderHelper $orderHelper, OrderStatusLog $orderStatusLog, OrderItem $orderItem)
  {
    $this->order = $order;
    $this->orderHelper = $orderHelper;
    $this->orderStatusLog = $orderStatusLog;
    $this->orderItem = $orderItem;
  }

  /**
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function createNewOrder(Request $request)
  {
    try {
      if (!$this->orderHelper->checkGuidHeader($request->header('x-deliveroo-sequence-guid'))) {
        return Response()->json(['message' => "INVALID GUID "], 404);
      }
      if (!$this->orderHelper->checkHmacHash($request->header('x-deliveroo-hmac-sha256'))) {
        return Response()->json(['message' => "INVALID HASH"], 404);
      }
      if (!$this->orderHelper->checkPayloadType($request->header('x-deliveroo-payload-type'))) {
        return Response()->json(['message' => "INVALID PAYLOAD TYPE"], 404);
      }
      if (!$this->orderHelper->checkVersion($request->header('x-deliveroo-webhook-version'))) {
        return Response()->json(['message' => "INVALID WEBHOOK VERSION"], 404);
      }

      $orderData = json_decode($request->getContent(), true);
      if ($orderData['event'] == self::ORDER_NEW_EVENT) {
        $orderData = $orderData['body']['order'];
        $orderData['order_id'] = $this->order->add($this->orderHelper->mapOrderData($orderData));
        if ($orderData['order_id'] && $this->orderHelper->addOrderStatusLog($orderData) && $this->orderHelper->addOrderItems($orderData)) {
          return Response()->json(['message' => "Success"], 200);
        } else {
          return Response()->json(['message' => "Failed"], 500);
        }
      } elseif ($orderData['event'] == self::ORDER_STATUS_UPDATE_EVENT) {
        $orderData = $orderData['body']['order'];
        $order = $this->order->where('order_number', $orderData['order_number'])->first();


      }
    } catch (\Exception $e) {
      Log::error($e->getMessage());
      return Response()->json(['message' => "Failed"], 500);
    }
  }

  /**
   * @param Request $request
   * @return void
   */
  public function addOrders(Request $request)
  {
    try {
      $flag = false;
      $content = json_decode($request->getContent(), true);
      if ($content['orders'] != null) {
        $orders = $content['orders'];
        foreach ($orders as $order) {
          //Add Order Data
          $order['order_id'] = $this->order->add($this->orderHelper->mapOrderData($order));
          //Add Order Logs
          $logs = $this->orderHelper->mapOrderStatusLogs($order);
          foreach ($logs as $log) {
            $this->orderStatusLog = new OrderStatusLog();
            $flag = $this->orderStatusLog->add($log);
          }
          //Add Order Items
          $items = $this->orderHelper->mapOrderItems($order);
          foreach ($items as $item) {
            $this->orderItem = new OrderItem();
            $flag = $this->orderItem->add($item);
          }
        }
        if ($flag == true) {
          echo "Successfully added order data";
        } else {
          echo "Failed to Add add order data";
        }
      }
    } catch (Exception $e) {
      Log::error($e->getMessage());
    }
  }
}
