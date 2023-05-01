<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Cookie;
use DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  public function index()
  {
    $token = Cookie::get('token_auth');
    $profile = DB::table('users')->where('token', $token)->first();

    $action_url = base_url('/profile/update');

    return view('profile.index', ['profile' => $profile, 'action_url' => $action_url]);
  }

  public function post_update(Request $request)
  {
    $token = Cookie::get('token_auth');
    $profile = DB::table('users')->where('token', $token)->first();

    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'confirmed'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'email' => $body['email'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    if (isset($body['password'])) {
      $data['password'] = Hash::make($body['password']);
    }

    DB::table('users')->where('id', $profile->id)->update($data);

    return redirect('/profile')->with('success_message', 'Berhasil merubah profil');
  }
}
