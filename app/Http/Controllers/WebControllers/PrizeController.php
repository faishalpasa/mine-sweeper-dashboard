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

    $periods = DB::table('prizes')->select('period')->groupBy('period')->orderBy('period', 'desc')->get();
    $selected_periods = $periods[0]->period ?? '';
    $query_period = $request->query('period') ?? $selected_periods;

    $prizes = DB::table('prizes')->where('period', $query_period)->select('*')->get();


    foreach ($periods as $idx => $period) {
      $exploded_period = explode('-', $period->period);
      $month_number = $exploded_period[1] ?? '';
      $period_year = $exploded_period[0] ?? '';

      $month_id = month_id($month_number) ?? '';

      $periods[$idx] = (object)[
        'label' => $month_id . ' ' . $period_year,
        'value' => $period->period,
      ];
    }

    return view('prize.index', ['prizes' => $prizes, 'periods' => $periods, 'query_period' => $query_period]);
  }

  public function create()
  {
    $prize = [
      'rank' => '',
      'name' => '',
      'image_url' => '',
      'period' => ''
    ];

    $action_url = base_url('/prize/create');

    return view('prize.form', [
      'prize' => $prize,
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
      'period' => 'required'
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
      'period' => date('Y') . '-' . $body['period'],
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

    $action_url = base_url('/prize/update/' . $id);

    return view('prize.form', [
      'prize' => $prize,
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
      'period' => 'required'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'rank' => $body['rank'],
      'name' => $body['name'],
      'period' => date('Y') . '-' . $body['period'],
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
