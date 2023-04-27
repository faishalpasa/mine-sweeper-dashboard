<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PlayerLogController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $player_logs = DB::table('player_logs')
      ->leftJoin('players', 'player_logs.player_id', 'players.id')
      ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
      ->select('player_logs.id', 'player_logs.score', 'player_logs.time', 'player_logs.created_at', 'players.name as player_name', 'players.msisdn as player_msisdn', 'players.coin as player_coin', 'levels.name as level_name')
      ->orderBy('id', 'desc')
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

    return view('player-log.index', ['player_logs' => $player_logs, 'periods' => $periods]);
  }
}
