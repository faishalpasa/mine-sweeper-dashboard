<?php

function base_url($path = '')
{
  $host = 'http://127.0.0.1:8000';
  return $host . $path;
}

function date_id($date)
{
  // format date must 2023-01-01
  $month = array(
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );
  $exploded_date = explode('-', $date);

  return $exploded_date[2] . ' ' . $month[(int)$exploded_date[1]] . ' ' . $exploded_date[0];
}

function month_id($month_number)
{
  $month = array(
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
  );

  return $month[(int)$month_number];
}
