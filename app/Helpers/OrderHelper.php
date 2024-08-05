<?php

namespace App\Helpers;

use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use App\Models\OrderItem;

class  OrderHelper
{
  private const guid = '149932323';

  private const secret = '83c9r6bq5q8qiplb7ml6980a878c8hgkrvql8v98s5bh2hrek6c';

  private const ORDER_NEW_PAYLOAD_TYPE = 'event/order.new';

  private const ORDER_STATUS_UPDATE_PAYLOAD_TYPE = 'event/order.status_update';

  private const WEBHOOK_VERSION = '1';


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
   * @param OrderStatus $orderStatus
   * @param OrderStatusLog $orderStatusLog
   * @param OrderItem $orderItem
   */
  public function __construct(
    OrderStatus    $orderStatus,
    OrderStatusLog $orderStatusLog,
    OrderItem      $orderItem
  )
  {
    $this->orderStatus = $orderStatus;
    $this->orderStatusLog = $orderStatusLog;
    $this->orderItem = $orderItem;
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

    return $data;
  }

  /**
   * @param $orderData
   * @return array
   */
  public function mapOrderStatusLogs($orderData)
  {
    $statusLogs = $orderData['status_log'];
    $prepStages=$orderData['prep_stages'];
    $count=0;
    foreach ($statusLogs as $logs)
    {
      $data[$count]['order_id'] = $orderData['order_id'];
      $data[$count]['status_id'] = $this->orderStatus->getOrderStatus($logs['status']);
      $data[$count]['at'] = $logs['at'];
      $count++;
    }
    foreach ($prepStages as $stage)
    {
      $data[$count]['order_id'] = $orderData['order_id'];
      $data[$count]['status_id'] = $this->orderStatus->getOrderStatus($stage['stage']);
      $data[$count]['at'] = $stage['occurred_at'];
      $count++;
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
    $count=0;
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

  public function checkHmacHash($hash)
  {
    $string = self::guid . self::secret;
    $hashedValue = hash('sha256', $string);
    return $hashedValue == $hash ? true : false;
  }

  public function checkVersion($version)
  {
    return self::WEBHOOK_VERSION == $version ? true : false;
  }

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
}
