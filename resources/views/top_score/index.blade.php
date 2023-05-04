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
      <div class="d-none d-sm-flex align-items-center">
        <i class="fas fa-crown me-1"></i>
        Daftar Top Skor
      </div>
      <div class="ms-auto me-0 ">
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
      </div>
      <div class="ms-1 me-0 d-flex gap-2">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
          <input type="text" class="form-control" placeholder="No. Handphone" aria-label="Name" onkeypress="handleSearch(event)" id="search-input" value="{{ $query_search }}">
        </div>
        <button class="btn btn-sm btn-secondary" onclick="handleSearchButton(event)">Cari</button>
        <button class="btn btn-sm btn-secondary" onclick="handleResetButton(event)">Reset</button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
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
      </div>

      {{ $players->links('layouts.pagination') }}

    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const handleSearch = (e) => {
    if (e.key === 'Enter') {
      const cleanUrl = window.location.href.split('?')[0]
      const url = new URL(cleanUrl)
      url.searchParams.set('search', e.target.value)
      window.location.href = url
    }
  }

  const handleSearchButton = (e) => {
    const searchKey = document.getElementById('search-input').value
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    url.searchParams.set('search', searchKey)
    @if($query_period)
    url.searchParams.set('period', {{$query_period}})
    @endif
    window.location.href = url
  }

  const handleResetButton = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    window.location.href = url
  }

  const handleChangePeriod = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    url.searchParams.set('period', e.target.value)
    @if($query_search)
    url.searchParams.set('search', {{$query_search}})
    @endif
    window.location.href = url
  }
</script>
@endsection
