<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ProfileController extends Controller
{
  public function index()
  {
    $profile = [
      'id' => 1,
      'name' => 'Administrator',
      'email' => 'administrator@email.com'
    ];

    return view('profile.index', ['profile' => $profile]);
  }

  public function post_update(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'confirmed'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/profile')->with('success_message', 'Berhasil merubah profil');
  }
}
