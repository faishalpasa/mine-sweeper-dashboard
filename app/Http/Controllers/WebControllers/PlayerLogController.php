<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlayerLogController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $player_logs = [
      [
        'id' => 1,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'level' => 1,
        'coin' => 1,
        'score' => 10,
        'total_score' => '100',
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 2,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'level' => 1,
        'coin' => 1,
        'score' => 10,
        'total_score' => 100,
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 3,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'level' => 1,
        'coin' => 1,
        'score' => 0,
        'total_score' => 100,
        'created_at' => '2023-03-01 00:00:00',
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

    return view('player-log.index', ['player_logs' => $player_logs, 'periods' => $periods]);
  }
}
