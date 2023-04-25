<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Str;

class PlayerController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $data = DB::table('players')->select('*')->orderBy('id', 'desc')->get();
    return view('player.index', ['players' => $data]);
  }

  public function create()
  {
    $player = [
      'name' => '',
      'msisdn' => '',
      'email' => '',
    ];

    $action_url = base_url('/player/create');

    return view('player.form', ['player' => $player, 'action_url' => $action_url]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'email' => 'required|email',
      'msisdn' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'email' => $body['email'],
      'msisdn' => $body['msisdn'],
      'token' => Str::random(20),
      'pin' => rand(100000, 999999),
      'status' => 1,
      'is_first_time_pin' => 1,
      'coin' => 5,
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('players')->insert($data);

    return redirect('/player')->with('success_message', 'Berhasil menambah pemain');
  }

  public function update($id)
  {
    $player = DB::table('players')
      ->where('id', $id)
      ->first();

    $action_url = base_url('/player/update/' . $id);

    return view('player.form', ['player' => $player, 'action_url' => $action_url]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'email' => 'required|email',
      'msisdn' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'email' => $body['email'],
      'msisdn' => $body['msisdn'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    DB::table('players')->where('id', $id)->update($data);

    return redirect('/player')->with('success_message', 'Berhasil mengubah pemain');
  }

  public function update_status($id)
  {
    $player = DB::table('players')
      ->where('id', $id)
      ->first();

    if ($player) {
      $status = $player->status == 1 ? 0 : 1;
      $data = [
        'status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
      ];

      DB::table('players')->where('id', $id)->update($data);
      return redirect('/player')->with('success_message', 'Berhasil mengubah status pemain');
    }
    return redirect('/player')->with('success_message', 'Player tidak ditemukan');
  }
}
