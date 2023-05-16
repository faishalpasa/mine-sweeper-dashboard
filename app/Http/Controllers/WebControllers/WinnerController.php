<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WinnerController extends Controller
{
  public function index(Request $request)
  {
    $query_period = $request->query('period') ?? null;

    $periods = DB::table('periods')
      ->where('end_at', '<', date('Y-m-d'))
      ->orderBy('start_at', 'desc')
      ->get();

    $first_period_id = $periods[0]->id ?? 0;
    $period_id = $query_period ?? $first_period_id;

    $selected_periods = DB::table('periods')
      ->where('id', $period_id)
      ->first();

    $prizes = DB::table('prizes')
      ->where('period_id', $selected_periods->id ?? 0)
      ->get();

    $prize_count = count($prizes);

    $s_date = date('Y-m-d 00:00:00', strtotime(date($selected_periods->start_at ?? 'Y-m-d')));
    $e_date = date('Y-m-d 23:59:59', strtotime(date($selected_periods->end_at ?? 'Y-m-d')));

    $players = DB::table('player_logs')
      ->leftJoin('players', 'player_logs.player_id', 'players.id')
      ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
      ->whereNotNull('players.id')
      ->where('player_logs.created_at', '>', $s_date)
      ->where('player_logs.created_at', '<', $e_date)
      ->select(
        'players.id as player_id',
        'players.name as player_name',
        'players.msisdn as player_msisdn',
        'players.email as player_email',
        DB::raw('SUM(player_logs.score) as total_score'),
        DB::raw('SUM(player_logs.time) as total_time'),
        DB::raw('MAX(levels.name) as max_level'),
      )
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->orderBy('total_time', 'asc')
      ->limit($prize_count)
      ->get();

    return view('winner.index', ['players' => $players, 'periods' => $periods, 'query_period' => $query_period]);
  }
}
