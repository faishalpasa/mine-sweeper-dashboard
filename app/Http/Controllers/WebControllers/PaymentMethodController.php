<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
  public function __construct()
  {
    $this->storage_directory = 'payment_method';
  }

  public function index(Request $request)
  {
    $payment_methods = DB::table('payment_methods')->select('*')->get();

    return view('payment_method.index', ['payment_methods' => $payment_methods]);
  }

  public function create()
  {
    $payment_method = [
      'name' => '',
      'account_no' => '',
      'image_src' => '',
      'is_active' => 1
    ];

    $action_url = base_url('/payment-method/create');

    return view('payment_method.form', [
      'payment_method' => $payment_method,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $body = $request->all();
    $validator = Validator::make($body, [
      'name' => 'required',
      'account' => 'required',
      'image_url' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:1024'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $name = $request->file('image_url')->getClientOriginalName();
    $file_path = Storage::disk('public')->putFileAs($this->storage_directory, $request->file('image_url'), date('YmdHis') . '_' . $name);

    $data = [
      'name' => $body['name'],
      'account' => $body['account'],
      'is_active' => 1,
      'image_url' => $file_path,
      'created_at' => date('Y-m-d H:i:s')
    ];

    DB::table('payment_methods')->insert($data);

    return redirect('/payment-method')->with('success_message', 'Berhasil menambah metode pembayaran');
  }

  public function update($id)
  {
    $payment_method = DB::table('payment_methods')
      ->where('id', $id)
      ->first();

    $action_url = base_url('/payment-method/update/' . $id);

    return view('payment_method.form', [
      'payment_method' => $payment_method,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $body = $request->all();
    $validator = Validator::make($body, [
      'name' => 'required',
      'account' => 'required',
      'image_url' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1024',
      'is_active' => 'numeric'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = [
      'name' => $body['name'],
      'account' => $body['account'],
      'is_active' => $body['is_active'],
      'updated_at' => date('Y-m-d H:i:s')
    ];

    if ($request->file('image_url')) {
      $name = $request->file('image_url')->getClientOriginalName();
      $file_path = Storage::disk('public')->putFileAs($this->storage_directory, $request->file('image_url'), date('YmdHis') . '_' . $name);

      array_push($data, ['image_url' => $file_path]);
    }

    DB::table('payment_methods')->where('id', $id)->update($data);

    return redirect('/payment-method')->with('success_message', 'Berhasil mengubah metode pembayaran');
  }
  public function delete($id)
  {
    DB::table('payment_methods')
      ->where('id', $id)
      ->delete();

    return redirect('/payment-method')->with('success_message', 'Berhasil menghapus metode pembayaran');
  }
}
