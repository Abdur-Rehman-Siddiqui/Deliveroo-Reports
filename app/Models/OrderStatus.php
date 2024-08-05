<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

  protected $table = 'order_status';

  public function getOrderStatus($status)
  {
    return $this->where('name', $status)->first()->id;
  }

}
