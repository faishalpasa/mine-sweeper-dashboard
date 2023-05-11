<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use DB;
use Response;
use Validator;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->user_rows = ['id', 'email', 'name', 'msisdn', 'is_first_time_pin', 'status'];
  }

  public function login(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
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
      $user = DB::table('players')
        ->select($this->user_rows)
        ->where('msisdn', $body['msisdn'])
        ->first();

      if ($user && $user->status == 0) {
        return Response::json([
          'success' =>  false,
          'code' =>  401,
          'message' =>  'Nomor handphone telah di banned, silakan daftar dengan nomor lain.'
        ], 401);
      }

      return Response::json([
        'success' => $user ? true : false,
        'code' => $user ? 200 : 404,
        'data' => $user,
        'message' =>  $user ? '' : 'Nomer HP tidak terdaftar, silahkan daftar terlebih dahulu dengan menekan tombol daftar.'
      ], $user ? 200 : 404);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function login_pin(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'pin' => 'required',
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
      $user = DB::table('players')
        ->select('token')
        ->where('msisdn', $body['msisdn'])
        ->where('pin', $body['pin'])
        ->first();

      return Response::json([
        'success' => $user ? true : false,
        'code' => $user ? 200 : 404,
        'data' => $user,
        'message' =>  $user ? '' : 'PIN salah, silahkan coba lagi.'
      ], $user ? 200 : 404);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function login_change_pin(Request $request)
  {
    $token = $request->header('x-token');
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
      'pin' => 'required',
      'new_pin' => 'required',
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
      $user = DB::table('players')
        ->select('token')
        ->where('msisdn', $body['msisdn'])
        ->where('token', $token)
        ->where('pin', $body['pin'])
        ->first();

      if ($user) {
        DB::table('players')
          ->where('msisdn', $body['msisdn'])
          ->where('pin', $body['pin'])
          ->where('token', $token)
          ->update(['pin' => $body['new_pin'], 'is_first_time_pin' => 0]);
      }

      return Response::json([
        'success' => $user ? true : false,
        'code' => $user ? 200 : 404,
        'data' => $user,
        'message' => $user ? 'Berhasil merubah pin.' : ''
      ], $user ? 200 : 404);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }

  public function login_reset_pin(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'msisdn' => 'required',
    ]);


    if ($validator->fails()) {
      $errors = $validator->errors();

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => $errors->first()
      ], 500);
    }

    $trim_msisdn = ltrim($body['msisdn'], '0');
    $msisdn = '+62' . $trim_msisdn;

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
          'message' => 'Silakan coba kembali dalam 1 menit lagi.'
        ], 409);
      }
    }

    try {
      $user = DB::table('players')
        ->select('token')
        ->where('msisdn', $body['msisdn'])
        ->first();

      if ($user) {
        $data = [
          'pin' => rand(1000, 9999),
          'is_first_time_pin' => 1,
          'updated_at' => date('Y-m-d H:i:s')
        ];

        DB::table('players')
          ->where('msisdn', $body['msisdn'])
          ->update($data);

        try {
          $request = Http::get('http://10.11.10.2:8080/send.php?phone=' . $msisdn . '&text=Kode%20PIN%20' . $data['pin']);

          $response = json_decode($request, true);
          $data_sms = [
            'msisdn' => $msisdn,
            'pin' => $data['pin'],
            'created_at' => date('Y-m-d H:i:s')
          ];
          DB::table('sms_send')->insert($data_sms);
        } catch (\Throwable $e) {
          // var_dump($e);
        }
      }

      return Response::json([
        'success' => $user ? true : false,
        'code' => $user ? 200 : 404,
        'data' => $user,
        'message' => $user ? 'Berhasil mereset pin.' : ''
      ], $user ? 200 : 404);
    } catch (\Throwable $e) {

      return Response::json([
        'success' => false,
        'code' => 500,
        'message' => 'Terjadi kesalahan ketika memproses data.'
      ], 500);
    }
  }
}
