<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class DailyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will add new orders daily in internal system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      try {
        $client = new \GuzzleHttp\Client();
        $username = "43s7og2osrpjtpl6lrs05v4nlq";
        $password = "b2jp15906nstkcprbk9o590148kla33p5sp52pi98krbntsa35p";
        $response = $client->request('GET', 'https://api.developers.deliveroo.com/order/v2/brand/brand_id/restaurant/restaurant_id/orders', [
          'headers' => [
            'accept' => 'application/json',
          ],
          'auth' => [
            $username,
            $password
          ],
        ]);

        echo $response->getBody();
      }
      catch (Exception $e)
      {
        Log::error($e->getMessage());
      }

    }
}
