<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;

class CoinPurchaseController extends Controller
{
  public function index(Request $request)
  {
    $search = $request->query('search') ?? '';

    $coin_purchases = DB::table('payments')
      ->leftJoin('players', 'payments.player_id', 'players.id')
      ->select('players.name as player_name', 'payments.*')
      ->where('players.name', 'LIKE', '%' . $search . '%')
      ->orWhere('payments.msisdn', 'LIKE', '%' . $search . '%')
      ->orderBy('id', 'desc')
      ->paginate(25)
      ->withQueryString();

    return view('coin_purchase.index', ['coin_purchases' => $coin_purchases, 'search' => $search]);
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
