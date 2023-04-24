<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;

class LevelController extends Controller
{
  public function index()
  {
    $levels = DB::table('levels')->select('*')->get();

    return view('level.index', ['levels' => $levels]);
  }

  public function create()
  {
    $level = [
      'name' => '',
      'cols' => '',
      'rows' => '',
      'mines' => '',
    ];

    $list_level = range(1, 50);

    $action_url = base_url('/level/create');

    return view('level.form', [
      'level' => $level,
      'list_level' => $list_level,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'cols' => 'required',
      'rows' => 'required',
      'mines' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'cols' => $body['cols'],
      'rows' => $body['rows'],
      'mines' => $body['mines'],
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('levels')->insert($data);

    return redirect('/level')->with('success_message', 'Berhasil menambah level');
  }

  public function update($id)
  {
    $level = DB::table('levels')->where('id', $id)->first();

    $list_level = range(1, 50);

    $action_url = base_url('/level/update/' . $id);

    return view('level.form', [
      'level' => $level,
      'list_level' => $list_level,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'cols' => 'required',
      'rows' => 'required',
      'mines' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'cols' => $body['cols'],
      'rows' => $body['rows'],
      'mines' => $body['mines'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    DB::table('levels')->where('id', $id)->update($data);

    return redirect('/level')->with('success_message', 'Berhasil mengubah level');
  }

  public function delete($id)
  {
    DB::table('levels')
      ->where('id', $id)
      ->delete();

    return redirect('/level')->with('success_message', 'Berhasil menghapus level');
  }
}
