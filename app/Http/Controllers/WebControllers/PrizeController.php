<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;

class PrizeController extends Controller
{
  public function __construct()
  {
    $this->storage_directory = 'prize';
  }

  public function index(Request $request)
  {
    $query_period = $request->query('period') ?? null;

    $periods = DB::table('periods')->orderBy('start_at', 'desc')->get();
    $selected_periods = DB::table('periods')
      ->where('start_at', '<', date('Y-m-d'))
      ->where('end_at', '>', date('Y-m-d'))
      ->first();

    $first_period_id = $selected_periods->id ?? 0;
    $period_id = $query_period ?? $first_period_id;

    $prizes = DB::table('prizes')
      ->where('period_id', $period_id)
      ->orderBy('rank', 'asc')
      ->select('*')
      ->get();

    return view('prize.index', ['prizes' => $prizes, 'periods' => $periods, 'query_period' => $period_id]);
  }

  public function create()
  {
    $prize = [
      'rank' => '',
      'name' => '',
      'image_url' => '',
      'period_id' => ''
    ];

    $periods = DB::table('periods')->orderBy('start_at', 'desc')->get();

    $action_url = base_url('/prize/create');

    return view('prize.form', [
      'prize' => $prize,
      'periods' => $periods,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'rank' => 'required|numeric',
      'name' => 'required',
      'image_url' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024',
      'period_id' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $name = $request->file('image_url')->getClientOriginalName();
    $file_path = Storage::disk('public')->putFileAs($this->storage_directory, $request->file('image_url'), date('YmdHis') . '_' . $name);

    $data = [
      'rank' => $body['rank'],
      'name' => $body['name'],
      'image_url' => $file_path,
      'period' => '',
      'period_id' => $body['period_id'],
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('prizes')->insert($data);

    return redirect('/prize')->with('success_message', 'Berhasil menambah metode pembayaran');
  }

  public function update($id)
  {
    $prize = DB::table('prizes')
      ->where('id', $id)
      ->first();

    $periods = DB::table('periods')->orderBy('start_at', 'desc')->get();

    $action_url = base_url('/prize/update/' . $id);

    return view('prize.form', [
      'prize' => $prize,
      'periods' => $periods,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();
    $validator = Validator::make($body, [
      'rank' => 'required|numeric',
      'name' => 'required',
      'image_url' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1024',
      'period_id' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'rank' => $body['rank'],
      'name' => $body['name'],
      'period_id' => $body['period_id'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    if ($request->file('image_url')) {
      $name = $request->file('image_url')->getClientOriginalName();
      $file_path = Storage::disk('public')->putFileAs($this->storage_directory, $request->file('image_url'), date('YmdHis') . '_' . $name);

      $data['image_url'] = $file_path;
    }

    DB::table('prizes')->where('id', $id)->update($data);

    return redirect('/prize')->with('success_message', 'Berhasil mengubah hadiah');
  }

  public function delete($id)
  {
    DB::table('prizes')
      ->where('id', $id)
      ->delete();

    return redirect('/prize')->with('success_message', 'Berhasil menghapus hadiah');
  }
}
