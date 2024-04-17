<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TopScoreController extends Controller
{
  public function index(Request $request)
  {
    $query_period = $request->query('period') ?? null;
    $query_search = $request->query('search') ?? null;

    $periods = DB::table('periods')->orderBy('start_at', 'desc')->get();

    $current_period = DB::table('periods')
      ->where('start_at', '<', date('Y-m-d'))
      ->where('end_at', '>', date('Y-m-d'))
      ->first();
    $first_period_id = $current_period->id ?? 0;
    $period_id = $query_period ?? $first_period_id;

    $selected_periods = DB::table('periods')
      ->where('id', $period_id)
      ->first();

    $s_date = date('Y-m-d 00:00:00', strtotime(date($selected_periods->start_at ?? 'Y-m-d')));
    $e_date = date('Y-m-d 23:59:59', strtotime(date($selected_periods->end_at ?? 'Y-m-d')));

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
        // DB::raw('MAX(levels.name) as max_level'),
      )
      ->where('player_logs.created_at', '>', $s_date)
      ->where('player_logs.created_at', '<', $e_date)
      ->where('players.msisdn', 'LIKE', '%' . $query_search . '%')
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->orderBy('total_time', 'asc')
      ->paginate(25)
      ->withQueryString();

    foreach ($players as $player) {
      $player_current_level = DB::table('player_logs')
        ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
        ->select('levels.name as level_name')
        ->where('player_logs.player_id', $player->player_id)
        ->orderBy('player_logs.id', 'desc')
        ->first();

      $player->max_level = $player_current_level->level_name ?? '';
    }

    dd($players);

    return view('top_score.index', [
      'players' => $players,
      'periods' => $periods,
      'query_search' => $query_search,
      'query_period' => $period_id
    ]);
  }
}
