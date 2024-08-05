<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  protected $table = 'orders';


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

     $this->save();
     return $this->id;
  }

}
