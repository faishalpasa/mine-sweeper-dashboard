<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;

class TermsController extends Controller
{
  public function index()
  {
    $terms = DB::table('terms')->select('*')->get();

    return view('terms.index', ['terms' => $terms]);
  }

  public function create()
  {
    $terms = [
      'title' => '',
      'description' => '',
    ];

    $action_url = base_url('/terms/create');

    return view('terms.form', [
      'terms' => $terms,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'title' => 'required',
      'description' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'title' => $body['title'],
      'description' => $body['description'],
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('terms')->insert($data);

    return redirect('/terms')->with('success_message', 'Berhasil menambah syarat dan ketentuan');
  }

  public function update($id)
  {
    $terms = DB::table('terms')->where('id', $id)->first();

    $action_url = base_url('/terms/update/' . $id);

    return view('terms.form', [
      'terms' => $terms,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();

    $validator = Validator::make($body, [
      'title' => 'required',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'title' => $body['title'],
      'description' => $body['description'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    DB::table('terms')->where('id', $id)->update($data);

    return redirect('/terms')->with('success_message', 'Berhasil mengubah syarat dan ketentuan');
  }

  public function delete($id)
  {
    DB::table('terms')
      ->where('id', $id)
      ->delete();

    return redirect('/terms')->with('success_message', 'Berhasil menghapus syarat dan ketentuan');
  }
}
