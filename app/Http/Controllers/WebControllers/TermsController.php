<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class TermsController extends Controller
{
  public function index()
  {
    $terms = [
      [
        'id' => '1',
        'title' => '',
        'description' => 'User dapat mulai memainkan game dengan cara register menggunakan nomer HP dan memasukkan PIN. Setelah itu user di minta untuk membuat PIN untuk login. Nantinya user untuk login setelah melakukan pendaftaran menggunakan nomer HP dan PIN saja.',
      ],
      [
        'id' => '2',
        'title' => '',
        'description' => 'Setelah daftar user mendapatkan Health/Nyawa secara gratis sebanyak 5 nyawa.',
      ],
      [
        'id' => '3',
        'title' => '',
        'description' => 'Setelah Nyawa habis user dapat membeli nyawa dengan Gopay atau Ovo yakni dengan pilihan Rp5.000 (10 nyawa) Rp10.000 (25 nyawa).',
      ],
      [
        'id' => '4',
        'title' => '',
        'description' => '3 Score tertinggi akan mendapatkan hadiah, contoh score tertinggi pertama mendapatkan HP, score tertinggi kedua mendapatkan smart watch dan score tertinggi ketiga mendapatkan pulsa.',
      ],
    ];

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
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'description' => 'required',
    ]);


    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/terms')->with('success_message', 'Berhasil menambah syarat dan ketentuan');
  }

  public function update($id)
  {
    $terms = [
      'id' => $id,
      'title' => 'Ini Judul',
      'description' => 'User dapat mulai memainkan game dengan cara register menggunakan nomer HP dan memasukkan PIN. Setelah itu user di minta untuk membuat PIN untuk login. Nantinya user untuk login setelah melakukan pendaftaran menggunakan nomer HP dan PIN saja.',
    ];

    $action_url = base_url('/terms/update/' . $id);

    return view('terms.form', [
      'terms' => $terms,
      'action_url' => $action_url
    ]);
  }

  public function post_update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return back()
        ->withErrors($validator)
        ->withInput();
    }

    return redirect('/terms')->with('success_message', 'Berhasil mengubah syarat dan ketentuan');
  }
}
