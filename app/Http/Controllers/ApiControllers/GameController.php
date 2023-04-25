<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Str;
use Response;

class GameController extends Controller
{
  public function get_prize(Request $request)
  {
    $current_period = date('Y-m');

    $prize = DB::table('prizes')
      ->where('period', $current_period)
      ->select('id', 'name', 'rank', 'image_url', 'period', 'id')
      ->get();

    if (!$prize) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    return Response::json([
      'success' => true,
      'code' => 200,
      'data' => $prize
    ], 200);
  }

  public function next_level(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();
    $first_level = DB::table('levels')->orderBy('id', 'asc')->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 200,
        'message' => 'Data tidak ditemukan.',
        'data' => [
          'coins' => 0,
          'level_id' => $first_level->id,
          'level' => $first_level->name,
          'points' => 0,
        ]
      ], 200);
    }

    try {
      $last_state = DB::table('player_logs')
        ->leftJoin('players', 'player_logs.player_id', 'players.id')
        ->leftJoin('levels', 'player_logs.level_id', 'levels.id')
        ->select(
          'players.id as player_id',
          'players.name as player_name',
          'players.msisdn as player_msisdn',
          'players.email as player_email',
          DB::raw('SUM(player_logs.score) as total_score'),
          DB::raw('SUM(player_logs.time) as total_time'),
          DB::raw('MAX(levels.id) as max_level'),
        )
        ->where('players.id', $player->id)
        ->groupBy('player_id')
        ->orderBy('total_score', 'desc')
        ->first();

      $next_level_id = DB::table('levels')
        ->where('id', '>', $last_state->max_level ?? '')
        ->min('id');

      $next_level = DB::table('levels')
        ->where('id', $next_level_id)
        ->first();

      $level_id = $next_level ?? $first_level;

      $data = [
        'state' => '',
        'score' => 0,
        'time' => 0,
        'level_id' => $level_id->id,
        'player_id' => $player->id,
        'created_at' => date('Y-m-d H:i:s')
      ];

      DB::table('player_logs')->insert($data);

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => [
          'coins' => $player->coin ?? 0,
          'level_id' => $level_id->id,
          'level' => $level_id->name,
          'points' => $last_state->total_score ?? 0,
        ]
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function top_score(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    $player = DB::table('players')->where('token', $token)->first();
    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    try {
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

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $players
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function winner(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    $player = DB::table('players')->where('token', $token)->first();
    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    try {
      $prev_period = date("Y-m", strtotime('-1 month', strtotime(date('Y-m-d'))));
      $s_date = date('Y-m-01 00:00:00', strtotime('-1 month', strtotime(date('Y-m-01'))));
      $l_date = date('Y-m-t 23:59:59', strtotime('-1 month', strtotime(date('Y-m-t'))));

      $prizes = DB::table('prizes')
        ->where('period', $prev_period)
        ->select('id', 'name', 'rank', 'image_url', 'period', 'id')
        ->get();

      $prize_count = count($prizes);

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
        ->limit($prize_count)
        ->get();

      $winners = [];

      foreach ($prizes as $idx => $prize) {
        $winners[$idx] = [
          'player_id' => $players[$idx]->player_id,
          'player_name' => $players[$idx]->player_name,
          'player_msisdn' => $players[$idx]->player_msisdn,
          'total_score' => $players[$idx]->total_score,
          'prize_name' => $prize->name,
        ];
      }

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $winners
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }
}
