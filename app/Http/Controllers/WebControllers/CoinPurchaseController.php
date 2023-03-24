<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CoinPurchaseController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->query('search') ?? '';

    $coin_purchases = [
      [
        'id' => 1,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'OVO',
        'coin' => 10,
        'status' => 1,
        'amount' => 5000,
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 2,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'Gopay',
        'coin' => 25,
        'status' => 1,
        'amount' => 10000,
        'created_at' => '2023-03-01 00:00:00',
      ],
      [
        'id' => 3,
        'name' => 'Test Pemain',
        'msisdn' => '081234567890',
        'invoice_no' => '1234567890',
        'payment_method_name' => 'OVO',
        'coin' => 10,
        'status' => 0,
        'amount' => 5000,
        'created_at' => '2023-03-01 00:00:00',
      ],
    ];

    return view('coin_purchase.index', ['coin_purchases' => $coin_purchases]);
  }

  public function create()
  {
    $coin_purchase = [
      'name' => '',
      'msisdn' => '',
      'email' => '',
    ];

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

    $action_url = base_url('/coin-purchase/create');

    return view('coin_purchase.form', [
      'coin_purchase' => $coin_purchase,
      'payment_methods' => $payment_methods,
      'action_url' => $action_url
    ]);
  }

  public function post_create(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'amount' => 'required',
      'payment_method_id' => 'required',
      'msisdn' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/coin-purchase')->with('success_message', 'Berhasil menambah pembelian koin');
  }

  public function update($id)
  {
    $coin_purchase = [
      'id' => $id,
      'name' => 'Test coin_purchase',
      'msisdn' => '08123',
      'email' => 'test@email.com',
    ];

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

    $action_url = base_url('/coin-purchase/update/' . $id);

    return view('coin_purchase.form', [
      'coin_purchase' => $coin_purchase,
      'payment_methods' => $payment_methods,
      'action_url' => $action_url
    ]);
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

    return redirect('/coin_purchase')->with('success_message', 'Berhasil mengubah pemain');
  }
}
