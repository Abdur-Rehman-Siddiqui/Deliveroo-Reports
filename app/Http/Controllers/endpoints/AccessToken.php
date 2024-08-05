<?php

namespace App\Http\Controllers\endpoints;

use App\Http\Controllers\Controller;

class AccessToken extends Controller
{

  public function getAccessToken()
  {
    $username = "43s7og2osrpjtpl6lrs05v4nlq";
    $password = "b2jp15906nstkcprbk9o590148kla33p5sp52pi98krbntsa35p";

    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://auth-sandbox.developers.deliveroo.com/oauth2/token', [
      'auth' => [
        $username,
        $password
      ],
      'form_params' => [
        'grant_type' => 'client_credentials'
      ],
      'headers' => [
        'accept' => 'application/json',
        'content-type' => 'application/x-www-form-urlencoded',
      ],
    ]);
    echo $response->getBody();
  }

}
