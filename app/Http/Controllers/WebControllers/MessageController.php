<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;

class MessageController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->query('search') ?? '';

    $messages = DB::table('messages')
      ->leftJoin('players', 'messages.player_id', 'players.id')
      ->select('players.name as player_name', 'players.msisdn as player_msisdn', 'messages.*')
      ->where('players.name', 'LIKE', '%' . $search . '%')
      ->orWhere('players.msisdn', 'LIKE', '%' . $search . '%')
      ->orderBy('id', 'desc')
      ->paginate(25)
      ->withQueryString();

    return view('message.index', ['messages' => $messages, 'search' => $search]);
  }

  public function create()
  {
    $message = [
      'name' => '',
      'message' => '',
    ];

    $action_url = base_url('/message/create');

    return view('message.form', [
      'message' => $message,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'message' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/message')->with('success_message', 'Berhasil menambah pesan baru');
  }

  public function update($id)
  {
    $message = DB::table('messages')
      ->leftJoin('players', 'messages.player_id', 'players.id')
      ->select('players.name as player_name', 'messages.*')
      ->where('id', $id)
      ->first();

    $action_url = base_url('/message/update/' . $id);

    return view('message.form', [
      'message' => $message,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'message' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/message')->with('success_message', 'Berhasil mengubah pesan');
  }
}
