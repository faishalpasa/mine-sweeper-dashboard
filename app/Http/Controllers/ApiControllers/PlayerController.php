<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Str;
use Response;

class PlayerController extends Controller
{

  public function create(Request $request)
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
        'code' => 500,
        'message' => 'Nomor handphone telah digunakan.'
      ], 500);
    }

    try {
      $data = [
        'msisdn' => $body['msisdn'],
        'token' => Str::random(20),
        'pin' => rand(100000, 999999),
        'status' => 1,
        'is_first_time_pin' => 1,
        'coin' => 0,
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

  public function get_profile(Request $request)
  {
    $token = $request->header('x-token');

    $player = DB::table('players')->where('token', $token)->select('name', 'email', 'pin', 'token', 'msisdn', 'id')->first();

    if (!$player) {
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
