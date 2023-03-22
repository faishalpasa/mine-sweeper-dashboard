<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
