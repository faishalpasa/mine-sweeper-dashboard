<?php

function base_url($path = '')
{
  $host = env('APP_URL');
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

function get_telco($msisdn)
{
  $telco = '';

  $INDOSAT_PREFIXES = ['0814', '0815', '0816', '0855', '0856', '0857', '0858'];
  $TELKOMSEL_PREFIXES = ['0811', '0812', '0813', '0821', '0822', '0851', '0852', '0853'];
  $EXCEL_PREFIXES = ['0817', '0818', '0819', '0859', '0877', '0878'];
  $AXIS_PREFIXES = ['0831', '0832', '0833', '0838'];
  $THREE_PREFIXES = ['0895', '0896', '0897', '0898', '0899'];
  $SMARTFREN_PREFIXES = [
    '0881',
    '0882',
    '0883',
    '0884',
    '0885',
    '0886',
    '0887',
    '0888',
    '0889'
  ];

  $msisdn_prefix = substr($msisdn, 0, 4);

  if (in_array($msisdn_prefix, $INDOSAT_PREFIXES)) {
    $telco = 'ISAT';
  } else if (in_array($msisdn_prefix, $TELKOMSEL_PREFIXES)) {
    $telco = 'TSEL';
  } else if (in_array($msisdn_prefix, $EXCEL_PREFIXES)) {
    $telco = 'EXCL';
  } else if (in_array($msisdn_prefix, $AXIS_PREFIXES)) {
    $telco = 'AXIS';
  } else if (in_array($msisdn_prefix, $THREE_PREFIXES)) {
    $telco = 'THRE';
  } else if (in_array($msisdn_prefix, $SMARTFREN_PREFIXES)) {
    $telco = 'FREN';
  }

  return $telco;
}
