@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Pemenang</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <div class="d-none d-sm-flex align-items-center">
        <i class="fas fa-trophy me-1"></i>
        Daftar Pemenang
      </div>
      <div class="ms-auto me-0 d-flex gap-2">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-calendar"></i>
          </span>
          <select class="form-select" onchange="handleChangePeriod(event)">
            <option disabled selected>Pilih Periode</option>
            @foreach ($periods as $period)
              <option {{$query_period == $period->id ? 'selected' : ''}} value="{{$period->id}}">
                {{date_id($period->start_at)}} - {{date_id($period->end_at)}}
              </option>
            @endforeach
          </select>
        </div>
        <button class="btn btn-sm btn-secondary" onclick="handleResetButton(event)">Reset</button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No. HP</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Level</th>
              <th>Total Skor</th>
              <th>Total Waktu</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($players as $player)
            <tr class="align-middle">
              <td>{{$player->player_msisdn}}</td>
              <td>{{$player->player_name}}</td>
              <td>{{$player->player_email}}</td>
              <td>{{$player->max_level}}</td>
              <td>{{number_format($player->total_score)}}</td>
              <td>{{date("i:s", $player->total_time / 1000)}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const handleChangePeriod = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    url.searchParams.set('period', e.target.value)
    window.location.href = url
  }

  const handleResetButton = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    window.location.href = url
  }
</script>
@endsection
