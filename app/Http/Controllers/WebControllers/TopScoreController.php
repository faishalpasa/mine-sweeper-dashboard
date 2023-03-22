<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopScoreController extends Controller
{
  public function index(Request $request)
  {
    $query_search = $request->query('search') ?? '';
    $query_period = $request->query('period') ?? '';

    $players = [
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

    $periods = [
      [
        'id' => '2',
        'start_date' => '2023-03-01',
        'end_date' => '2023-03-31',
        'status' => '1'
      ],
      [
        'id' => '1',
        'start_date' => '2023-02-01',
        'end_date' => '2023-02-28',
        'status' => '0'
      ]
    ];

    foreach ($periods as $idx => $period) {
      $start_date = explode('-', $period['start_date'])[2] ?? '';
      $end_date = explode('-', $period['end_date'])[2] ?? '';

      $date_id = date_id($period['end_date']) ?? '';
      $month_id = explode(' ', $date_id)[1] ?? '';
      $year_id = explode(' ', $date_id)[2] ?? '';

      $periods[$idx] = [
        'id' => $period['id'],
        'label' => $start_date . ' - ' . $end_date . ' ' . $month_id . ' ' . $year_id
      ];
    }

    return view('top_score.index', ['players' => $players, 'periods' => $periods]);
  }
}
