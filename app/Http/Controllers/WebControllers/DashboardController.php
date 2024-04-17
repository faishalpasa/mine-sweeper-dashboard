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
    $current_period = DB::table('periods')
      ->where('start_at', '<', date('Y-m-d'))
      ->where('end_at', '>', date('Y-m-d'))
      ->first();
    $first_period_id = $current_period->id ?? 0;
    $period_id = $query_period ?? $first_period_id;

    $selected_periods = DB::table('periods')
      ->where('id', $period_id)
      ->first();

    $period_start_date = date('Y-m-d 00:00:00', strtotime(date($selected_periods->start_at ?? 'Y-m-d')));
    $period_end_date = date('Y-m-d 23:59:59', strtotime(date($selected_periods->end_at ?? 'Y-m-d')));

    $total_players = DB::table('players')->count();

    $total_coin_purchases = DB::table('payments')->count();

    $coin_purchases_per_period = DB::table('payments')
      ->where('created_at', '>', $period_start_date)
      ->where('created_at', '<', $period_end_date)
      ->count();

    $revenue = DB::table('payments')
      ->where('status', 'success')
      ->select(DB::raw('SUM(amount) as total_amount'))
      ->first();

    $total_revenue = $revenue->total_amount;

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
      ->where('player_logs.created_at', '>', $period_start_date)
      ->where('player_logs.created_at', '<', $period_end_date)
      ->whereNotNull('players.id')
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->limit(10)
      ->get();
    // foreach ($top_scores as $player) {
    //   $player_current_level = DB::table('player_logs')
    //     ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
    //     ->select('levels.name as level_name')
    //     ->where('player_logs.player_id', $player->player_id)
    //     ->orderBy('player_logs.id', 'desc')
    //     ->first();

    //   $top_scores->max_level = $player_current_level->level_name ?? '';
    // }

    $coin_purchases = DB::table('payments')
      ->leftJoin('players', 'payments.player_id', 'players.id')
      ->select('players.name as player_name', 'payments.*')
      ->orderBy('id', 'desc')
      ->limit(10)
      ->get();

    return view('dashboard', [
      'total_players' => $total_players,
      'total_coin_purchases' => $total_coin_purchases,
      'coin_purchases_per_period' => $coin_purchases_per_period,
      'total_revenue' => $total_revenue,
      'top_scores' => $top_scores,
      'coin_purchases' => $coin_purchases
    ]);
  }
}
