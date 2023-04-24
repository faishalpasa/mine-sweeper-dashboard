<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use DB;
use Response;
use Validator;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->user_rows = ['id', 'email', 'name', 'msisdn', 'is_first_time_pin'];
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
        ->where('status', 1)
        ->first();

      return Response::json([
        'success' => $user ? true : false,
        'code' => $user ? 200 : 404,
        'data' => $user,
        'message' =>  $user ? '' : 'Nomor handphone tidak ditemukan.'
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
        'message' =>  $user ? '' : 'PIN yang kamu masukan salah.'
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
}
