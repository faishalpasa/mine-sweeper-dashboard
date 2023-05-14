<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Str;
use Response;
use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{

  public function pre_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $check_msisdn = DB::table('players')->where('msisdn', $body['msisdn'])->first();
    if ($check_msisdn) {
      return Response::json([
        'success' => false,
        'code' => 409,
        'message' => 'Nomor handphone telah digunakan.'
      ], 409);
    }

    $trim_msisdn = ltrim($body['msisdn'], '0');
    $msisdn = '+62' . $trim_msisdn;
    $pin = rand(1000, 9999);

    $check_sms = DB::table('sms_send')
      ->where('msisdn', $msisdn)
      ->orderBy('created_at', 'desc')
      ->first();

    if ($check_sms) {
      $date_last_sms = date_create(date($check_sms->created_at));
      $date_now = date_create(date('Y-m-d H:i:s'));
      $diference = date_diff($date_last_sms, $date_now);

      $minutes_left = 1 - (int)$diference->i;
      $seconds_left = 60 - (int)$diference->s;
      if ((int)$minutes_left > 0 && (int)$seconds_left > 0) {
        return Response::json([
          'success' => false,
          'code' => 409,
          'message' => 'SMS sudah terkirim ke nomor HP Anda. Silakan coba kembali dalam 1 menit lagi.'
        ], 409);
      }
    }

    try {
      $request = Http::get('http://10.11.10.2:8080/send.php?phone=' . $msisdn . '&text=Kode%20PIN%20' . $pin);
      $response = json_decode($request, true);

      $data_sms = [
        'msisdn' => $msisdn,
        'pin' => $pin,
        'created_at' => date('Y-m-d H:i:s')
      ];
      DB::table('sms_send')->insert($data_sms);

      return Response::json([
        'success' => true,
        'code' => 200,
        'message' => 'SMS terkirim.'
      ], 200);
    } catch (\Throwable $e) {
      // $response_error = json_decode($e, true);
      // dd($e->getMessage());
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika mengirim SMS.'
      ], 500);
    }
  }

  public function pre_create_check(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'pin' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    try {
      $trim_msisdn = ltrim($body['msisdn'], '0');
      $msisdn = '+62' . $trim_msisdn;
      $pin = $body['pin'];

      $check_sms = DB::table('sms_send')
        ->where('msisdn', $msisdn)
        ->where('pin', $pin)
        ->orderBy('created_at', 'desc')
        ->first();

      if ($check_sms) {
        return Response::json([
          'success' => true,
          'code' => 200,
          'data' => $check_sms
        ], 200);
      } else {
        return Response::json([
          'success' => false,
          'code' => 404,
          'message' => 'PIN salah, silahkan coba lagi.'
        ], 404);
      }
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function msisdn_check($msisdn)
  {
    try {
      $check_msisdn = DB::table('players')->select('id', 'msisdn', 'name', 'email')->where('msisdn', $msisdn)->first();

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $check_msisdn
      ], 200);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'pin' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $check_msisdn = DB::table('players')->where('msisdn', $body['msisdn'])->first();
    if ($check_msisdn) {
      return Response::json([
        'success' => false,
        'code' => 409,
        'message' => 'Nomor handphone telah digunakan.'
      ], 409);
    }

    try {
      $data = [
        'msisdn' => $body['msisdn'],
        'token' => Str::random(20),
        'pin' => $body['pin'],
        'status' => 1,
        'is_first_time_pin' => 1,
        'is_game_over' => 0,
        'coin' => 5,
        'created_at' => date('Y-m-d H:i:s')
      ];

      $id = DB::table('players')->insertGetId($data);
      $new_player = DB::table('players')->where('id', $id)->first();

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $new_player
      ], 200);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    $body = $request->all();
    $token = $request->header('x-token');

    $validator = Validator::make($body, [
      'name' => 'nullable',
      'email' => 'nullable|email',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    if (isset($body['email'])) {
      $check_email = DB::table('players')->where('email', $body['email'])->where('token', '!=', $token)->first();

      if ($check_email) {
        return Response::json([
          'success' => false,
          'code' => 500,
          'message' => 'Email telah digunakan.'
        ], 500);
      }
    }


    try {
      $data = [
        'updated_at' => date('Y-m-d H:i:s')
      ];

      if (isset($body['email'])) {
        $data['email'] = $body['email'];
      }

      if (isset($body['name'])) {
        $data['name'] = $body['name'];
      }

      DB::table('players')->where('id', $id)->update($data);

      $player = DB::table('players')->where('id', $id)->select('name', 'email', 'pin', 'token', 'msisdn', 'id')->first();

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $player
      ], 200);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function terms()
  {
    try {
      $terms = DB::table('terms')->select('*')->orderBy('id', 'desc')->get();
      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $terms
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function save_log(Request $request)
  {
    $body = $request->all();
    $token = $request->header('x-token');

    $validator = Validator::make($body, [
      'data' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'data' => ['message' => $errors->first()]
      ], 500);
    }

    $player = DB::table('players')->where('token', $token)->first();
    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    try {
      $decoded_data = base64_decode($body['data']);
      $decoded_json = json_decode($decoded_data, true);

      $state = base64_decode($decoded_json['decodedStateName']);
      $score = base64_decode($decoded_json['decodedPointsName']);
      $time = base64_decode($decoded_json['decodedTimeName']);
      $level_id = base64_decode($decoded_json['decodedLevelName']);

      $data = [
        'state' => $state ?? '',
        'score' => $score ?? '',
        'time' => $time ?? '',
        'level_id' => $level_id ?? '',
        'player_id' => $player->id,
        'created_at' => date('Y-m-d H:i:s')
      ];

      DB::table('player_logs')->insert($data);

      if ($score == 0) {
        DB::table('players')->where('id', $player->id)->update([
          'is_game_over' => 1,
        ]);
      }

      $s_date = date('Y-m-01 00:00:00');
      $e_date = date('Y-m-t 23:59:59');

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
        ->where('player_logs.created_at', '>', $s_date)
        ->where('player_logs.created_at', '<', $e_date)
        ->where('players.id', $player->id)
        ->groupBy('players.id')
        ->orderBy('total_score', 'desc')
        ->orderBy('total_time', 'asc')
        ->first();

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => $last_state
      ], 200);
    } catch (\Throwable $e) {
      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function get_log(Request $request)
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
          'columns' => $first_level->cols,
          'rows' => $first_level->rows,
          'mines' => $first_level->mines,
          'state' => '',
        ]
      ], 200);
    }

    try {
      $current_period = DB::table('periods')
        ->where('start_at', '<=', date('Y-m-d'))
        ->where('end_at', '>', date('Y-m-d'))
        ->first();
      $first_period_id = $current_period->id ?? 0;
      $period_id = $query_period ?? $first_period_id;

      $selected_period = DB::table('periods')
        ->where('id', $period_id)
        ->first();

      $s_date = date('Y-m-d 00:00:00', strtotime(date($selected_period->start_at ?? 'Y-m-d')));
      $e_date = date('Y-m-d 23:59:59', strtotime(date($selected_period->end_at ?? 'Y-m-d')));

      if (!$selected_period) {
        return Response::json([
          'success' => false,
          'code' => 200,
          'data' => [
            'columns' => 10,
            'rows' => 10,
            'mines' => 0,
            'state' => '',
            'is_game_over' => true
          ],
          'message' => 'Saat ini belum ada periode permainan yang aktif.'
        ], 200);
      }

      $last_state = DB::table('player_logs')
        ->where('player_id', $player->id)
        ->where('created_at', '>', $s_date)
        ->where('created_at', '<', $e_date)
        ->orderBy('id', 'desc')
        ->first();

      $last_level = DB::table('levels')
        ->where('id', $last_state->level_id ?? '')
        ->orderBy('id', 'desc')
        ->first();

      $level = $last_level ?? $first_level;

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => [
          'columns' => $level->cols,
          'rows' => $level->rows,
          'mines' => $level->mines,
          'state' => $last_state->state ?? '',
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

  public function get_data(Request $request)
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
      $current_period = DB::table('periods')
        ->where('start_at', '<', date('Y-m-d'))
        ->where('end_at', '>', date('Y-m-d'))
        ->first();
      $first_period_id = $current_period->id ?? 0;
      $period_id = $query_period ?? $first_period_id;

      $selected_period = DB::table('periods')
        ->where('id', $period_id)
        ->first();

      $s_date = date('Y-m-d 00:00:00', strtotime(date($selected_period->start_at ?? 'Y-m-d')));
      $e_date = date('Y-m-d 23:59:59', strtotime(date($selected_period->end_at ?? 'Y-m-d')));

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
        ->where('player_logs.created_at', '>', $s_date)
        ->where('player_logs.created_at', '<', $e_date)
        ->where('players.id', $player->id)
        ->groupBy('players.id')
        ->orderBy('total_score', 'desc')
        ->first();


      $last_level = DB::table('levels')
        ->where('id', $last_state->max_level ?? '')
        ->orderBy('id', 'desc')
        ->first();

      $level = $last_level ?? $first_level;

      $last_time = DB::table('player_logs')
        ->select(
          DB::raw('SUM(player_logs.time) as total_time'),
        )
        ->where('created_at', '>', $s_date)
        ->where('created_at', '<', $e_date)
        ->where('player_id', $player->id)
        ->where('level_id', $level->id)
        ->orderBy('id', 'desc')
        ->first();

      return Response::json([
        'success' => true,
        'code' => 200,
        'data' => [
          'coins' => (int)$player->coin ?? 0,
          'level_id' => (int)$level->id ?? 1,
          'level' => $level->name ?? 1,
          'points' => $last_state ? (int)$last_state->total_score : 0,
          'time' => $last_time ? (int)$last_time->total_time : 0,
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

  public function get_profile(Request $request)
  {
    $token = $request->header('x-token');

    $player = DB::table('players')->where('token', $token)->select('name', 'email', 'pin', 'token', 'msisdn', 'id', 'coin', 'is_game_over', 'status')->first();

    if (!$player) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    if ($player->status == 0) {
      return Response::json([
        'success' => false,
        'code' => 404,
        'message' => 'Data tidak ditemukan.'
      ], 404);
    }

    return Response::json([
      'success' => true,
      'code' => 200,
      'data' => $player
    ], 200);
  }
}
