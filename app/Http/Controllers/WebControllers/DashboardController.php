<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use DB;

class DashboardController extends Controller
{
  public function index()
  {

    $s_date = date('Y-m-d 00:00:00');
    $e_date = date('Y-m-d 23:59:59');

    $total_players = DB::table('players')->count();
    $total_coin_purchases = DB::table('payments')->count();
    $coin_purchases_per_day = DB::table('payments')
      ->where('created_at', '>', $s_date)
      ->where('created_at', '<', $e_date)
      ->count();
    $revenue = DB::table('payments')
      ->where('status', 'success')
      ->select(DB::raw('SUM(amount) as total_amount'))
      ->first();
    $total_revenue = $revenue->total_amount;

    $current_period = DB::table('periods')
      ->where('start_at', '<', date('Y-m-d'))
      ->where('end_at', '>', date('Y-m-d'))
      ->first();
    $first_period_id = $current_period->id ?? 0;
    $period_id = $query_period ?? $first_period_id;

    $selected_periods = DB::table('periods')
      ->where('id', $period_id)
      ->first();

    $ts_s_date = date('Y-m-d 00:00:00', strtotime(date($selected_periods->start_at ?? 'Y-m-d')));
    $ts_e_date = date('Y-m-d 23:59:59', strtotime(date($selected_periods->end_at ?? 'Y-m-d')));

    $top_scores = DB::table('player_logs')
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
      ->where('player_logs.created_at', '>', $ts_s_date)
      ->where('player_logs.created_at', '<', $ts_e_date)
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->limit(10)
      ->get();

    $coin_purchases = DB::table('payments')
      ->leftJoin('players', 'payments.player_id', 'players.id')
      ->select('players.name as player_name', 'payments.*')
      ->orderBy('id', 'desc')
      ->limit(10)
      ->get();

    return view('dashboard', [
      'total_players' => $total_players,
      'total_coin_purchases' => $total_coin_purchases,
      'coin_purchases_per_day' => $coin_purchases_per_day,
      'total_revenue' => $total_revenue,
      'top_scores' => $top_scores,
      'coin_purchases' => $coin_purchases
    ]);
  }
}
