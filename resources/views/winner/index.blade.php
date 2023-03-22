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
      <i class="fas fa-trophy me-1"></i>
      Daftar Pemenang
      <div class="ms-auto me-0">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-calendar"></i>
          </span>
          <select class="form-select" onchange="handleChangePeriod(event)">
            @foreach ($periods as $idx => $period)
              <option {{$idx === 0 ? 'selected' : ''}} value="{{$period['id']}}">{{$period['label']}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No. Handphone</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Level</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($players as $player)
          <tr class="align-middle">
            <td>{{$player['msisdn']}}</td>
            <td>{{$player['name']}}</td>
            <td>{{$player['email']}}</td>
            <td>{{$player['level']}}</td>
            <td>{{$player['score']}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection
