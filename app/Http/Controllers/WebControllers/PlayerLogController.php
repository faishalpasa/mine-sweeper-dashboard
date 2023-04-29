<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PlayerLogController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->query('search') ?? '';

    $periods = DB::table('prizes')
      ->select('period')
      ->groupBy('period')
      ->orderBy('period', 'desc')
      ->get();

    $selected_periods = date('Y-m');
    $query_period = $request->query('period') ?? $selected_periods;

    $s_date = date('Y-m-01 00:00:00', strtotime(date($query_period)));
    $e_date = date('Y-m-t 23:59:59', strtotime(date($query_period)));

    foreach ($periods as $idx => $period) {
      $exploded_period = explode('-', $period->period);
      $month_number = $exploded_period[1] ?? '';
      $period_year = $exploded_period[0] ?? '';

      $month_id = month_id($month_number) ?? '';

      $periods[$idx] = (object)[
        'label' => $month_id . ' ' . $period_year,
        'value' => $period->period,
      ];
    }

    $player_logs = DB::table('player_logs')
      ->leftJoin('players', 'player_logs.player_id', 'players.id')
      ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
      ->select('player_logs.id', 'player_logs.score', 'player_logs.time', 'player_logs.created_at', 'players.name as player_name', 'players.msisdn as player_msisdn', 'players.coin as player_coin', 'levels.name as level_name')
      ->orderBy('id', 'desc')
      ->where('players.name', 'LIKE', '%' . $search . '%')
      ->orWhere('players.msisdn', 'LIKE', '%' . $search . '%')
      ->where('player_logs.created_at', '>', $s_date)
      ->where('player_logs.created_at', '<', $e_date)
      ->paginate(25)
      ->withQueryString();

    return view('player-log.index', ['player_logs' => $player_logs, 'periods' => $periods, 'search' => $search, 'query_period' => $query_period]);
  }
}
