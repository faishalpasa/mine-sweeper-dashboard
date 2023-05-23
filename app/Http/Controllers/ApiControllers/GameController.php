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
  public function get_prize()
  {
    try {
      $selected_periods = DB::table('periods')
        ->where('start_at', '<=', date('Y-m-d'))
        ->where('end_at', '>', date('Y-m-d'))
        ->orderBy('start_at', 'asc')
        ->first();

      $current_period_id = $selected_periods->id ?? 0;
      $prizes = DB::table('prizes')
        ->where('period_id', $current_period_id)
        ->select('id', 'name', 'rank', 'image_url', 'period', 'id')
        ->get();


      if (!$prizes) {
        return Response::json([
          'success' => false,
          'code' => 404,
          'message' => 'Data tidak ditemukan.'
        ], 404);
      }

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $prizes
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function next_level(Request $request)
  {
    $token = $request->header('x-token');
    $player = DB::table('players')->where('token', $token)->first();
    $first_level = DB::table('levels')->orderBy('id', 'asc')->first();
    $max_level = DB::table('levels')->orderBy('id', 'desc')->first();

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

      if ($max_level->id == $last_state->max_level) {
        return Response::json([
          'success' => false,
          'code' => 409,
          'message' => 'Anda telah mencapai level maksimal.',
        ], 409);
      }

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
    // $token = $request->header('x-token');
    // $player = DB::table('players')->where('token', $token)->first();

    // if (!$player) {
    //   return Response::json([
    //     'success' => false,
    //     'code' => 404,
    //     'message' => 'Data tidak ditemukan.'
    //   ], 404);
    // }

    try {
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
          DB::raw('MAX(levels.name) as max_level'),
        )
        ->where('player_logs.created_at', '>', $s_date)
        ->where('player_logs.created_at', '<', $e_date)
        ->whereNotNull('players.id')
        ->groupBy('players.id')
        ->orderBy('total_score', 'desc')
        ->orderBy('total_time', 'asc')
        ->limit(10)
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
    // $token = $request->header('x-token');
    // $player = DB::table('players')->where('token', $token)->first();
    // if (!$player) {
    //   return Response::json([
    //     'success' => false,
    //     'code' => 404,
    //     'message' => 'Data tidak ditemukan.'
    //   ], 404);
    // }

    try {
      $periods = DB::table('periods')
        ->where('end_at', '<', date('Y-m-d'))
        ->orderBy('start_at', 'desc')
        ->get();

      $first_period_id = $periods[0]->id ?? null;
      $period_id = $query_period ?? $first_period_id;

      $selected_periods = DB::table('periods')
        ->where('id', $period_id)
        ->first();

      $prizes = DB::table('prizes')
        ->where('period_id', $selected_periods->id)
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

      $winners = [];

      foreach ($prizes as $idx => $prize) {
        if (isset($players[$idx])) {
          $winners[$idx] = [
            'player_id' => $players[$idx]->player_id,
            'player_name' => $players[$idx]->player_name,
            'player_msisdn' => $players[$idx]->player_msisdn,
            'total_score' => $players[$idx]->total_score,
            'prize_name' => $prize->name,
          ];
        }
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

    $api_url = env('XENDIT_URL');
    $secret = env('XENDIT_SECRET_KEY');
    $public = env('XENDIT_PUBLIC_KEY');
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
      ])->post($api_url . '/ewallets/charges', $data);

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

    $api_url = env('XENDIT_URL');
    $secret = env('XENDIT_SECRET_KEY');
    $public = env('XENDIT_PUBLIC_KEY');
    $token = $secret . ':' . $public;
    $encoded_token = base64_encode($token);

    try {
      $request_http = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->get($api_url . '/ewallets/charges/' . $id);

      $response = json_decode($request_http, true);

      $data = [
        'status' => $response['status'] == 'SUCCEEDED' ? 'success' : $response['status'],
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

        $date_register = date_create(date($player->created_at));
        $date_now = date_create(date('Y-m-d H:i:s'));
        $date_diference = date_diff($date_register, $date_now);
        $coin_purchases = DB::table('payments')->where('player_id', $player->id)->get();
        $trx_id = $request->query('trx_id');

        if ($date_diference->m < 1 && count($coin_purchases) < 1) {
          $trim_msisdn = ltrim($player->msisdn, '0');
          $msisdn = '+62' . $trim_msisdn;
          $telco = get_telco($player->msisdn);
          $price = $response['charge_amount'] ?? 0;

          $postback_url = env('POSTBACK_URL');
          try {
            $replace_trx_id = str_replace('{trx_id}', $trx_id, $postback_url);
            $replace_msisdn = str_replace('{msisdn}', $msisdn, $replace_trx_id);
            $replace_telco = str_replace('{telco}', $telco, $replace_msisdn);
            $replace_price = str_replace('{price}', $price, $replace_telco);
            $full_postback_url = $replace_price;
            Http::get($full_postback_url);
          } catch (\Throwable $e) {
          }
        }
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

    $api_url = env('MIDTRANS_URL');
    $server = env('MIDTRANS_SERVER_KEY');
    $client = env('MIDTRANS_CLIENT_KEY');
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
      $request_http = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->post($api_url . '/v2/charge', $data);

      $response = json_decode($request_http, true);

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

    $api_url = env('MIDTRANS_VERITRANS_URL');
    $server = env('MIDTRANS_SERVER_KEY');
    $client = env('MIDTRANS_CLIENT_KEY');
    $token = $server . ':' . $client;
    $encoded_token = base64_encode($token);

    try {
      $request_http = Http::withHeaders([
        'Authorization' => 'Basic ' . $encoded_token,
      ])->get($api_url . '/v2/' . $id . '/status');

      $response = json_decode($request_http, true);

      $data = [
        'status' => $response['transaction_status'] == 'settlement' ? 'success' : $response['transaction_status'],
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

        $date_register = date_create(date($player->created_at));
        $date_now = date_create(date('Y-m-d H:i:s'));
        $date_diference = date_diff($date_register, $date_now);
        $coin_purchases = DB::table('payments')->where('player_id', $player->id)->get();
        $trx_id = $request->query('trx_id');
        if ($date_diference->m < 1 && count($coin_purchases) < 1) {
          $trim_msisdn = ltrim($player->msisdn, '0');
          $msisdn = '+62' . $trim_msisdn;
          $telco = get_telco($player->msisdn);
          $price = $response['gross_amount'];

          $postback_url = env('POSTBACK_URL');
          try {
            $full_postback_url = $postback_url . 'type=mo&transaction_id=' . $trx_id . '&msisdn=' . $msisdn . '&telco=' . $telco . '&price=' . $price;
            Http::get($full_postback_url);
          } catch (\Throwable $e) {
          }
        }
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

  public function post_message(Request $request)
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
      'message' => 'required|string|max:1000',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $check_limit = DB::table('messages')
      ->where('player_id', $player->id)
      ->whereDate('created_at', date('Y-m-d'))
      ->count();

    if ($check_limit > 4) {
      return Response::json([
        'success' => false,
        'code' => 403,
        'message' => 'Anda mencapai batas maksimal pengiriman pesan, coba lagi besok.'
      ], 403);
    }

    try {
      $data = [
        'message' => $body['message'],
        'player_id' => $player->id,
        'created_at' => date('Y-m-d H:i:s')
      ];

      DB::table('messages')->insert($data);

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
}
