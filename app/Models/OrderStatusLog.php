<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
  use HasFactory;

  protected $table = 'order_status_logs';

  public $timestamps = false;

  public function add($data)
  {
    $this->status_id = $data['status_id'];
    $this->order_id = $data['order_id'];
    $this->at = $data['at'];
    return $this->save();
  }
}
