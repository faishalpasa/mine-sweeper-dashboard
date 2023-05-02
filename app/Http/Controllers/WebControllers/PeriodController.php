<?php

namespace App\Http\Controllers\WebControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class PeriodController extends Controller
{
  public function index()
  {
    $periods = DB::table('periods')->orderBy('start_at', 'desc')->get();

    return view('period.index', ['periods' => $periods]);
  }

  public function create()
  {
    $period = [
      'name' => '',
      'start_at' => '',
      'end_at' => '',
    ];

    $action_url = base_url('/period/create');

    return view('period.form', [
      'period' => $period,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'start_at' => 'required',
      'end_at' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'start_at' => $body['start_at'],
      'end_at' => $body['end_at'],
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('periods')->insert($data);

    return redirect('/period')->with('success_message', 'Berhasil menambah periode permainan');
  }

  public function update($id)
  {
    $period = DB::table('periods')->where('id', $id)->first();

    $action_url = base_url('/period/update/' . $id);

    return view('period.form', [
      'period' => $period,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'name' => 'required',
      'start_at' => 'required',
      'end_at' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'start_at' => $body['start_at'],
      'end_at' => $body['end_at'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    DB::table('periods')->where('id', $id)->update($data);

    return redirect('/period')->with('success_message', 'Berhasil mengubah periode permainan');
  }
}
