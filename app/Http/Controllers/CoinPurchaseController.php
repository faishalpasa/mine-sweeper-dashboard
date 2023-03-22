<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoinPurchaseController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $coin_purchases = [
      [
        'id' => 1,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'OVO',
        'coin' => 10,
        'status' => 1,
        'amount' => 5000,
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 2,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'Gopay',
        'coin' => 25,
        'status' => 1,
        'amount' => 10000,
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 3,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'OVO',
        'coin' => 10,
        'status' => 0,
        'amount' => 5000,
        'created_at' => '2023-03-01 00:00:00',
      ],
    ];

    return view('coin_purchase.index', ['coin_purchases' => $coin_purchases]);
  }
}
