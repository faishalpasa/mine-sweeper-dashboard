<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
