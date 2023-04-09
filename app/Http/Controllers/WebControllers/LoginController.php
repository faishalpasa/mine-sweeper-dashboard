<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cookie;
use DB;

class LoginController extends Controller
{
  public function __construct()
  {
    $this->user_rows = ['id', 'email', 'password', 'token'];
  }

  public function index()
  {
    return view('login');
  }

  public function login(Request $request)
  {
    $body = $request->all();

    $user = DB::table('users')
      ->select($this->user_rows)
      ->where('email', $body['email'])
      ->first();

    if (!$user) {
      return back()->with('error_message', 'Email tidak terdaftar salah')->withInput();
    }

    $check_password = Hash::check($body['password'], $user->password);

    if (!$check_password) {
      return back()->with('error_message', 'Password tidak sesuai')->withInput();
    }

    $minutes = 10080; // 1 week
    Cookie::queue('token_auth', $user->token, $minutes);

    return redirect('/');
  }

  public function logout(Request $request)
  {
    Cookie::queue(Cookie::forget('token_auth'));

    return redirect('/login');
  }
}
