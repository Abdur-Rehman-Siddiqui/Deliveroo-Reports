<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory;

  protected $table = 'restaurants';

  public function Items()
  {
    return $this->hasMany(Item::class);
  }
  public function Menus()
  {
    return $this->hasMany(Menu::class);
  }

  public function MenuItems()
  {
    return $this->hasMany(MenuItem::class);
  }

  public function Orders()
  {
    return $this->hasMany(Order::class);
  }

  public function OrderItems()
  {
    return $this->hasMany(OrderItem::class);
  }

  public function Users()
  {
    return $this->hasMany(User::class);
  }



}
