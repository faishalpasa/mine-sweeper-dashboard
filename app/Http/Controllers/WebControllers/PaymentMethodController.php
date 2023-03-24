<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class PaymentMethodController extends Controller
{
  public function index(Request $request)
  {
    $payment_methods = [
      [
        'id' => '1',
        'name' => 'OVO',
        'image_url' => 'https://placehold.co/200x200?text=OVO',
        'account_no' => '1234567890',
        'is_active' => 1,
      ],
      [
        'id' => '2',
        'name' => 'Gopay',
        'image_url' => 'https://placehold.co/200x200?text=Gopay',
        'account_no' => '1234567890',
        'is_active' => 1,
      ],
    ];


    return view('payment_method.index', ['payment_methods' => $payment_methods]);
  }

  public function create()
  {
    $payment_method = [
      'name' => '',
      'account_no' => '',
      'image_url' => '',
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
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'account_no' => 'required',
      'image_url' => 'required|image'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/payment-method')->with('success_message', 'Berhasil menambah metode pembayaran');
  }

  public function update($id)
  {
    $payment_method = [
      'id' => $id,
      'name' => 'OVO',
      'image_url' => 'https://placehold.co/200x200?text=OVO',
      'account_no' => '1234567890',
      'is_active' => 1,
    ];

    $action_url = base_url('/payment-method/update/' . $id);

    return view('payment_method.form', [
      'payment_method' => $payment_method,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'account_no' => 'required',
      'image_url' => 'image'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/payment-method')->with('success_message', 'Berhasil mengubah metode pembayaran');
  }
}
