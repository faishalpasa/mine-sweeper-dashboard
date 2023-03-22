<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrizeController extends Controller
{
  public function index(Request $request)
  {
    $query_period = $request->query('period') ?? '';

    $prizes = [
      [
        'id' => '1',
        'name' => 'Hadiah Pertama',
        'image_url' => 'https://placehold.co/200x200?text=Hadiah+Pertama',
        'rank' => '1',
      ],
      [
        'id' => '2',
        'name' => 'Hadiah Kedua',
        'image_url' => 'https://placehold.co/200x200?text=Hadiah+Kedua',
        'rank' => '2',
      ],
      [
        'id' => '3',
        'name' => 'Hadiah Ketiga',
        'image_url' => 'https://placehold.co/200x200?text=Hadiah+Ketiga',
        'rank' => '3',
      ],
    ];

    $periods = [
      [
        'id' => '2',
        'start_date' => '2023-03-01',
        'end_date' => '2023-03-31',
        'status' => '1'
      ],
      [
        'id' => '1',
        'start_date' => '2023-02-01',
        'end_date' => '2023-02-28',
        'status' => '0'
      ]
    ];

    foreach ($periods as $idx => $period) {
      $start_date = explode('-', $period['start_date'])[2] ?? '';
      $end_date = explode('-', $period['end_date'])[2] ?? '';

      $date_id = date_id($period['end_date']) ?? '';
      $month_id = explode(' ', $date_id)[1] ?? '';
      $year_id = explode(' ', $date_id)[2] ?? '';

      $periods[$idx] = [
        'id' => $period['id'],
        'label' => $start_date . ' - ' . $end_date . ' ' . $month_id . ' ' . $year_id
      ];
    }

    return view('prize.index', ['prizes' => $prizes, 'periods' => $periods]);
  }
}
