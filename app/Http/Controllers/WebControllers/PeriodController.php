<?php

namespace App\Http\Controllers\WebControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class PeriodController extends Controller
{
  public function index()
  {
    $periods = [
      [
        'id' => '1',
        'title' => 'Periode Maret',
        'start_at' => '2023-03-01',
        'end_at' => '2023-03-31',
        'status' => 1,
      ],
      [
        'id' => '2',
        'title' => 'Periode Februari',
        'start_at' => '2023-02-01',
        'end_at' => '2023-02-28',
        'status' => 0,
      ],
      [
        'id' => '1',
        'title' => 'Periode Natal',
        'start_at' => '2023-01-01',
        'end_at' => '2023-01-31',
        'status' => 0,
      ],
    ];

    return view('period.index', ['periods' => $periods]);
  }

  public function create()
  {
    $period = [
      'title' => '',
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
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'start_at' => 'required',
      'end_at' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/period')->with('success_message', 'Berhasil menambah periode permainan');
  }

  public function update($id)
  {
    $period = [
      'id' => $id,
      'title' => 'Periode Maret',
      'start_at' => '2023-03-01',
      'end_at' => '2023-03-31',
      'status' => 1,
    ];

    $action_url = base_url('/period/update/' . $id);

    return view('period.form', [
      'period' => $period,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'start_at' => 'required',
      'end_at' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/period')->with('success_message', 'Berhasil mengubah periode permainan');
  }
}
