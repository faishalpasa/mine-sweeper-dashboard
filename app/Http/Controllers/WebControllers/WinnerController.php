<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WinnerController extends Controller
{
  public function index(Request $request)
  {
    $query_period = $request->query('period') ?? '';

    $s_date = date('Y-m-d 00:00:00');
    $l_date = date('Y-m-t 23:59:59');

    $players = DB::table('player_logs')
      ->leftJoin('players', 'player_logs.player_id', 'players.id')
      ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
      ->select(
        'players.id as player_id',
        'players.name as player_name',
        'players.msisdn as player_msisdn',
        'players.email as player_email',
        DB::raw('SUM(player_logs.score) as total_score'),
        DB::raw('SUM(player_logs.time) as total_time'),
        DB::raw('MAX(levels.name) as max_level'),
      )
      ->where('player_logs.created_at', '>', $s_date)
      ->where('player_logs.created_at', '<', $l_date)
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->get();

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

    return view('winner.index', ['players' => $players, 'periods' => $periods]);
  }
}
