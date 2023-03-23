<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;

class DashboardController extends Controller
{
  public function index()
  {
    $total_players = 100;
    $total_coin_purchases = 10;
    $coin_purchases_per_day = 5;
    $total_revenue = 100000;

    $top_scores = [
      [
        'id' => '1',
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'score' => '1000'
      ],
      [
        'id' => '2',
        'name' => 'Test Pemain 2',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'score' => '2000'
      ],
      [
        'id' => '3',
        'name' => 'Test Pemain 3',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'score' => '3000'
      ],
    ];

    $registered_players = [
      [
        'date' => '2023-03-01',
        'total' => 12
      ],
      [
        'date' => '2023-03-07',
        'total' => 21
      ],
      [
        'date' => '2023-03-14',
        'total' => 12
      ],
      [
        'date' => '2023-03-21',
        'total' => 9
      ],
      [
        'date' => '2023-03-28',
        'total' => 11
      ]
    ];

    $coin_purchases = [
      [
        'date' => '2023-03-01',
        'total' => 5
      ],
      [
        'date' => '2023-03-07',
        'total' => 2
      ],
      [
        'date' => '2023-03-14',
        'total' => 3
      ],
      [
        'date' => '2023-03-21',
        'total' => 5
      ],
      [
        'date' => '2023-03-28',
        'total' => 6
      ]
    ];

    return view('dashboard', [
      'total_players' => $total_players,
      'total_coin_purchases' => $total_coin_purchases,
      'coin_purchases_per_day' => $coin_purchases_per_day,
      'total_revenue' => $total_revenue,
      'top_scores' => $top_scores,
      'registered_players' => $registered_players,
      'coin_purchases' => $coin_purchases
    ]);
  }
}
