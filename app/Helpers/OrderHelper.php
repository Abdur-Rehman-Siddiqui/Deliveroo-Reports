<?php

namespace App\Helpers;

use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use App\Models\OrderItem;
use App\Models\Order;
use DateTime;

class  OrderHelper
{
  private const guid = '149932323';

  private const secret = '83c9r6bq5q8qiplb7ml6980a878c8hgkrvql8v98s5bh2hrek6c';

  private const ORDER_NEW_PAYLOAD_TYPE = 'event/order.new';

  private const ORDER_STATUS_UPDATE_PAYLOAD_TYPE = 'event/order.status_update';

  private const WEBHOOK_VERSION = '1';


  public const ORDER_STATUS_PLACED = '1';


  public const ORDERS_STATUS_REJECTED = '2';

  public const ORDER_PENDING_ACCEPTED = '3';

  public const ORDER_STATUS_CONFIRMED = '4';

  public const ORDER_STATUS_CANCELED = '5';

  public const ORDER_STATUS_PENDING = '6';

  public const ORDER_STATUS_DELIVERED = '7';

  public const ORDER_STATUS_IN_KITCHEN = '8';

  public const ORDER_STATUS_READY_FOR_COLLECTION = '9';

  public const ORDER_STATUS_READY_FOR_COLLECTION_SOON = '10';

  public const ORDER_STATUS_COLLECTED = '11';


  /**
   * @var OrderStatus
   */
  protected $orderStatus;
  /**
   * @var OrderStatusLog
   */
  protected $orderStatusLog;
  /**
   * @var OrderItem
   */
  protected $orderItem;
  /**
   * @var Order
   */
  protected $order;

  /**
   * @param OrderStatus $orderStatus
   * @param OrderStatusLog $orderStatusLog
   * @param OrderItem $orderItem
   * @param Order $order
   */
  public function __construct(
    OrderStatus    $orderStatus,
    OrderStatusLog $orderStatusLog,
    OrderItem      $orderItem,
    Order          $order
  )
  {
    $this->orderStatus = $orderStatus;
    $this->orderStatusLog = $orderStatusLog;
    $this->orderItem = $orderItem;
    $this->order = $order;
  }

  /**
   * @param $orderData
   * @return array
   */
  public function mapOrderData($orderData)
  {
    $data['order_number'] = $orderData['order_number'];
    $data['restaurant_id'] = $orderData['brand_id'];
    $data['status_id'] = $this->orderStatus->getOrderStatus($orderData['status']);
    $data['subtotal'] = $orderData['subtotal']['fractional'];
    $data['total_price'] = $orderData['total_price']['fractional'];
    $data['delivery_fee'] = $orderData['delivery']['delivery_fee']['fractional'];
    $data['surcharge'] = $orderData['surcharge']['fractional'];
    $data['offer_discount'] = $orderData['offer_discount']['fractional'];
    $data['customer_loyalty'] = $orderData['customer']['loyalty']['card_number'];
    $data['prepare_for'] = $orderData['prepare_for'];
    $data['deliveroo_order_id'] = $orderData['id'];
    foreach ($orderData['status_log'] as $statusLog) {
      if ($statusLog['status'] == 'pending') {
        $data['order_date'] = $this->changeDateFormat($statusLog['at']);
      }
    }
    return $data;
  }

  /**
   * @param $orderData
   * @return array
   */
  public function mapOrderStatusLogs($orderData)
  {
    $statusLogs = $orderData['status_log'];
    $count = 0;
    foreach ($statusLogs as $logs) {
      $data[$count]['order_id'] = $orderData['order_id'];
      $data[$count]['status_id'] = $this->orderStatus->getOrderStatus($logs['status']);
      $data[$count]['at'] = $logs['at'];
      $count++;
    }
    if (array_key_exists('prep_stages', $orderData)) {
      $prepStages = $orderData['prep_stages'];
      foreach ($prepStages as $stage) {
        $data[$count]['order_id'] = $orderData['order_id'];
        $data[$count]['status_id'] = $this->orderStatus->getOrderStatus($stage['stage']);
        $data[$count]['at'] = $stage['occurred_at'];
        $count++;
      }
    }

    return $data;
  }


  /**
   * @param $orderData
   * @return array
   */
  public function mapOrderItems($orderData)
  {
    $items = $orderData['items'];
    $count = 0;
    foreach ($items as $item) {
      $data[$count]['order_id'] = $orderData['order_id'];
      $data[$count]['restaurant_id'] = $orderData['brand_id'];
      $data[$count]['unit_price'] = $item['unit_price']['fractional'];
      $data[$count]['menu_unit_price'] = $item['menu_unit_price']['fractional'];
      $data[$count]['quantity'] = $item['quantity'];
      $data[$count]['cost_per_unit'] = $item['item_fees'][0]['cost_per_unit']['fractional'];
      $data[$count]['discount_amount'] = $item['discount_amount']['fractional'];
    }
    return $data;
  }

  /**
   * @param $guid
   * @return bool
   */
  public function checkGuidHeader($guid)
  {
    return self::guid == $guid ? true : false;
  }

  /**
   * @param $hash
   * @return bool
   */
  public function checkHmacHash($hash)
  {
    $string = self::guid . self::secret;
    $hashedValue = hash('sha256', $string);
    return $hashedValue == $hash ? true : false;
  }

  /**
   * @param $version
   * @return bool
   */
  public function checkVersion($version)
  {
    return self::WEBHOOK_VERSION == $version ? true : false;
  }

  /**
   * @param $type
   * @return bool
   */
  public function checkPayloadType($type)
  {
    if (self::ORDER_NEW_PAYLOAD_TYPE == $type) {
      return true;
    } else if (self::ORDER_STATUS_UPDATE_PAYLOAD_TYPE == $type) {
      return true;
    } else {
      return false;
    }
  }


  public function getPreprationTime($orders)
  {
    $intervals = [];
    foreach ($orders as $order) {
      $logs = $order->StatusLogs()->whereIn('status_id', [3, 11])->get();
      if (count($logs) < 2) {
        continue;
      }
      $acceptedTime = new DateTime($logs[0]['at']);
      $collectionTime = new DateTime($logs[1]['at']);
      $interval = $collectionTime->diff($acceptedTime);
      $intervals[] = $interval->i;

    }

  }

  public function changeDateFormat($date)
  {
    $date = new DateTime($date);
    return $date->format('Y-m-d H:i:s');
  }

  /**
   * @param $fromDate
   * @param $toDate
   * @param $statusId
   * @return mixed
   */
  public function getOrdersInTimeFrame($fromDate, $toDate, $statusId = null)
  {
    $this->order= new Order();
    $orderQuery=$this->order->query();
    $orderQuery->where('order_date', '>=', $fromDate)->where('order_date', '<=', $toDate);
    if ($statusId != null) {
      $orderQuery->where('status_id', $statusId);
    }
    return $orderQuery->get();
  }

  /**
   *  This method returns  percentage
   *
   * @param $totalOrders
   * @param $totalDeliveredOrders
   * @return float|int
   */
  public function calculatePercentage($numerator, $denominator)
  {
    if ($numerator > 0 && $denominator > 0) {
      return (($numerator / $denominator) * 100);
    }
    return 0;
  }

  /**
   * calculate gross sale of orders
   *
   * @param $orders
   * @return int|mixed
   */
  public function calculateTotalPrice($orders)
  {
    $totalPrice=0;
    if($orders!=null)
    {
      foreach ($orders as $order)
      {
        $totalPrice=$totalPrice+$order->total_price;
      }
    }
    return $totalPrice;
  }

}
