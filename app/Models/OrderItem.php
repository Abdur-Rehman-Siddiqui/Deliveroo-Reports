<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  use HasFactory;

  protected $table = 'order_items';
  public $timestamps = false;

  protected $fillable = ['restaurant_id', 'order_id', 'unit_price', 'menu_unit_price', 'quantity', 'cost_per_unit', 'discount_amount'];

  public function add($data)
  {
    $this->restaurant_id = $data['restaurant_id'];
    $this->order_id = $data['order_id'];
    $this->unit_price = $data['unit_price'];
    $this->menu_unit_price = $data['menu_unit_price'];
    $this->quantity = $data['quantity'];
    $this->cost_per_unit = $data['cost_per_unit'];
    $this->discount_amount = $data['discount_amount'];
    return $this->save();
  }
}
