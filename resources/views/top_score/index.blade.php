@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Top Skor</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-crown me-1"></i>
      Daftar Top Skor
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
      <div class="ms-1 me-0">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
          <input type="text" class="form-control" placeholder="Nama / No. Handphone" aria-label="Name" onkeypress="handleSearch(event)">
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
            <td>{{$player->player_msisdn}}</td>
            <td>{{$player->player_name}}</td>
            <td>{{$player->player_email}}</td>
            <td>{{$player->max_level}}</td>
            <td>{{number_format($player->total_score)}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <nav>
        <ul class="pagination justify-content-end">
          <li class="page-item disabled">
            <span class="page-link">
              <span aria-hidden="true">&laquo;</span>
            </span>
          </li>
          <li class="page-item"><a class="page-link text-black" href="#">1</a></li>
          <li class="page-item"><a class="page-link text-black" href="#">2</a></li>
          <li class="page-item"><a class="page-link text-black" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link text-black" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const handleSearch = (e) => {
    if (e.key === 'Enter') {
      const url = new URL(window.location.href)
      url.searchParams.set('search', e.target.value)
      window.location.href = url
    }
  }

  const handleChangePeriod = (e) => {
    const url = new URL(window.location.href)
    url.searchParams.set('period', e.target.value)
    window.location.href = url
  }
</script>
@endsection
