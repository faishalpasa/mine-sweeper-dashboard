<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PlayerController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $data = [
      [
        'id' => '1',
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '1'
      ],
      [
        'id' => '2',
        'name' => 'Test Pemain 2',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '1'
      ],
      [
        'id' => '3',
        'name' => 'Test Pemain 3',
        'msisdn' => '081234567890',
        'email' => 'test@email.com',
        'level' => '1',
        'status' => '0'
      ],
    ];
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
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'msisdn' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/player')->with('success_message', 'Berhasil menambah pemain');
  }

  public function update($id)
  {
    $player = [
      'id' => $id,
      'name' => 'Test Player',
      'msisdn' => '08123',
      'email' => 'test@email.com',
    ];

    $action_url = base_url('/player/update/' . $id);

    return view('player.form', ['player' => $player, 'action_url' => $action_url]);
  }

  public function post_update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'msisdn' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/player')->with('success_message', 'Berhasil mengubah pemain');
  }
}
