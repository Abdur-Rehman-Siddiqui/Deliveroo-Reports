<?php

namespace App\Models;

use App\Models\OrderStatusLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
class Order extends Model
{
  use HasFactory;

  protected $table = 'orders';


  public function StatusLogs() {
    return $this->hasMany(OrderStatusLog::class, 'order_id', 'id');
  }

  public function Items() {
    return $this->hasMany(OrderItem::class, 'order_id', 'id');
  }

  public function getById($id) {
    return $this->find($id);
  }

  public function add($data)
  {
    $this->order_number = $data['order_number'];
    $this->deliveroo_order_id=$data['deliveroo_order_id'];
    $this->restaurant_id = $data['restaurant_id'];
    $this->status_id = $data['status_id'];
    $this->subtotal = $data['subtotal'];
    $this->total_price = $data['total_price'];
    $this->delivery_fee = $data['delivery_fee'];
    $this->surcharge = $data['surcharge'];
    $this->offer_discount = $data['offer_discount'];
    $this->customer_loyalty = $data['customer_loyalty'];
    $this->prepare_for = $data['prepare_for'];
    $this->order_date=$data['order_date'];

     $this->save();
     return $this->id;
  }

}
