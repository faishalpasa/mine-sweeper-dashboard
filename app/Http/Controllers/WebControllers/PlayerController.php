<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $data = [
      [
        'id' => '1',
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '1'
      ],
      [
        'id' => '2',
        'name' => 'Test Pemain 2',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '1'
      ],
      [
        'id' => '3',
        'name' => 'Test Pemain 3',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '0'
      ],
    ];
    return view('player.index', ['players' => $data]);
  }
}
