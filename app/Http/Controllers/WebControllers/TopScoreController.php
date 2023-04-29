<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TopScoreController extends Controller
{
  public function index(Request $request)
  {
    $query_search = $request->query('search') ?? '';
    $query_period = $request->query('period') ?? '';

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
      ->where('players.name', 'LIKE', '%' . $query_search . '%')
      ->orWhere('players.msisdn', 'LIKE', '%' . $query_search . '%')
      ->where('player_logs.created_at', '>', $s_date)
      ->where('player_logs.created_at', '<', $e_date)
      ->groupBy('players.id')
      ->orderBy('total_score', 'desc')
      ->orderBy('total_time', 'asc')
      ->paginate(25)
      ->withQueryString();

    return view('top_score.index', ['players' => $players, 'periods' => $periods, 'search' => $query_search, 'query_period' => $query_period]);
  }
}
