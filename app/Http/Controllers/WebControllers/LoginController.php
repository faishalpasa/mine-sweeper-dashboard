<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;

class LoginController extends Controller
{
  public function index()
  {
    return view('login');
  }

  public function login(Request $request)
  {
    $minutes = 10080; // 1 week
    Cookie::queue('token_auth', '123123', $minutes);

    return redirect('/');
  }

  public function logout(Request $request)
  {
    Cookie::queue(Cookie::forget('token_auth'));

    return redirect('/login');
  }
}
