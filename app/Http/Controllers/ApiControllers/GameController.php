<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    try {
      $s_date = date('Y-m-01 00:00:00');
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

  public function continue_play(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    try {
      DB::table('players')->where('id', $player->id)->update([
        'coin' => $player->coin - 1,
        'is_game_over' => 0
      ]);

      return Response::json([
        'success' => true,
        'code' => 200,
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function pay_ovo(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'amount' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $secret = 'xnd_development_2o3w1rtPbLnnxtzEVTnmiN1iLepXwWj7lzDhdEoOvdtFr4ny7ZlVXzDtC5S';
    $public = 'xnd_public_development_WUML4s5jBq4T5PtiankpljGSY9jz7PEfA3ckcsITSSDGE5gYgwwNIGkaiYAHU';
    $token = $secret . ':' . $public;
    $encoded_token = base64_encode($token);

    try {
      $data = [
        'reference_id' => 'order-id-' . date('YmdHis'),
        'currency' => 'IDR',
        'amount' => $body['amount'],
        'checkout_method' => 'ONE_TIME_PAYMENT',
        'channel_code' => 'ID_OVO',
        'channel_properties' => [
          'mobile_number' => $body['msisdn']
        ]
      ];
      $request = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->post('https://api.xendit.co/ewallets/charges', $data);

      $response = json_decode($request, true);

      $data = [
        'player_id' => $player->id,
        'channel' => $response['channel_code'],
        'amount' => $response['charge_amount'],
        'currency' => $response['currency'],
        'reference_id' => $response['reference_id'],
        'invoice_no' => $response['id'],
        'status' => $response['status'] == 'PENDING' ? 'pending' : 'success',
        'msisdn' => $response['channel_properties']['mobile_number'],
        'created_at' => date('Y-m-d H:i:s')
      ];

      DB::table('payments')->insert($data);
      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $response
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function pay_ovo_check(Request $request, $id)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    $secret = 'xnd_development_2o3w1rtPbLnnxtzEVTnmiN1iLepXwWj7lzDhdEoOvdtFr4ny7ZlVXzDtC5S';
    $public = 'xnd_public_development_WUML4s5jBq4T5PtiankpljGSY9jz7PEfA3ckcsITSSDGE5gYgwwNIGkaiYAHU';
    $token = $secret . ':' . $public;
    $encoded_token = base64_encode($token);

    try {
      $request = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->get('https://api.xendit.co/ewallets/charges/' . $id);

      $response = json_decode($request, true);

      $data = [
        'status' => $response['status'] == 'PENDING' ? 'pending' : 'success',
        'updated_at' => date('Y-m-d H:i:s')
      ];

      DB::table('payments')
        ->where('invoice_no', $response['id'])
        ->where('player_id', $player->id)
        ->update($data);

      if ($response['status'] == 'SUCCEEDED') {
        $coin = 0;
        if ($response['charge_amount'] == 10000) {
          $coin = 10;
        } else if ($response['charge_amount'] == 15000) {
          $coin = 25;
        }

        DB::table('players')
          ->where('id', $player->id)
          ->update(['coin' => $player->coin + $coin]);
      }

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $response
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function pay_gopay(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'amount' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $server = 'SB-Mid-server-KfTpJDOEU4aLhgdPtzf3g9IL';
    $client = 'SB-Mid-client-NTXL6SyccpDovLoo';
    $token = $server . ':' . $client;
    $encoded_token = base64_encode($token);

    try {
      $data = [
        'transaction_details' =>
        [
          'order_id' => 'order-id-' . date('YmdHis'),
          'gross_amount' => $body['amount'],
        ],
        "payment_type" => "gopay",
      ];
      $request = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->post('https://api.sandbox.midtrans.com/v2/charge', $data);

      $response = json_decode($request, true);

      $data = [
        'player_id' => $player->id,
        'channel' => $response['payment_type'],
        'amount' => (int)$response['gross_amount'],
        'currency' => $response['currency'],
        'reference_id' => $response['order_id'],
        'invoice_no' => $response['transaction_id'],
        'status' => $response['transaction_status'] == 'pending' ? 'pending' : 'success',
        'msisdn' => $body['msisdn'],
        'created_at' => date('Y-m-d H:i:s')
      ];

      DB::table('payments')->insert($data);
      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $response
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function pay_gopay_check(Request $request, $id)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    $server = 'SB-Mid-server-KfTpJDOEU4aLhgdPtzf3g9IL';
    $client = 'SB-Mid-client-NTXL6SyccpDovLoo';
    $token = $server . ':' . $client;
    $encoded_token = base64_encode($token);

    try {
      $request = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->get('https://api.sandbox.veritrans.co.id/v2/' . $id . '/status');

      $response = json_decode($request, true);

      $data = [
        'status' => $response['transaction_status'] == 'pending' ? 'pending' : 'success',
        'updated_at' => date('Y-m-d H:i:s')
      ];

      DB::table('payments')
        ->where('invoice_no', $response['transaction_id'])
        ->where('player_id', $player->id)
        ->update($data);

      if ($response['transaction_status'] == 'settlement') {
        $coin = 0;
        if ((int)$response['gross_amount'] == 10000) {
          $coin = 10;
        } else if ((int)$response['gross_amount'] == 15000) {
          $coin = 25;
        }

        DB::table('players')
          ->where('id', $player->id)
          ->update(['coin' => $player->coin + $coin]);
      }

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $response
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
