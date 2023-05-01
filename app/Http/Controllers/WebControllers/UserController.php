<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Str;
use DB;
use Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  static function get_auth()
  {
    $token = Cookie::get('token_auth');
    $user = DB::table('users')->where('token', $token)->first();
    return $user;
  }

  public function index()
  {
    $auth = $this->get_auth();
    $users = DB::table('users')
      ->leftJoin('roles', 'users.role_id', 'roles.id')
      ->select('users.name', 'users.email', 'roles.name as role_name', 'users.id', 'users.role_id')
      ->get();

    return view('user.index', ['users' => $users, 'auth' => $auth]);
  }

  public function create()
  {
    $user = [
      'name' => '',
      'email' => '',
      'role_id' => '',
    ];

    $roles = DB::table('roles')->select('*')->get();

    $action_url = base_url('/user/create');

    return view('user.form', [
      'user' => $user,
      'roles' => $roles,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'email' => 'required',
      'name' => 'required',
      'role_id' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'email' => $body['email'],
      'role_id' => $body['role_id'],
      'password' => Hash::make('123456'),
      'token' => Str::random(20),
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('users')->insert($data);

    return redirect('/user')->with('success_message', 'Berhasil menambah user dashboard');
  }

  public function update($id)
  {
    $user = DB::table('users')->where('id', $id)->first();

    $action_url = base_url('/user/update/' . $id);

    return view('terms.form', [
      'user' => $user,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'email' => 'required',
      'name' => 'required',
      'role_id' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'email' => $body['email'],
      'role_id' => $body['role_id'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    DB::table('users')->where('id', $id)->update($data);

    return redirect('/user')->with('success_message', 'Berhasil mengubah user dashboard');
  }

  public function delete($id)
  {
    if ($id != 1) {
      DB::table('users')
        ->where('id', $id)
        ->delete();

      return redirect('/user')->with('success_message', 'Berhasil menghapus syarat dan ketentuan');
    } else {
      return redirect('/user')->with('error_message', 'Administrator tidak dapat dihapus');
    }
  }
}
