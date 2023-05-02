@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Log Permainan</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-server me-1"></i>
      Daftar Log Permainan
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
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>No. Handphone</th>
            <th>Nama</th>
            <th>Level</th>
            <th>Koin</th>
            <th>Waktu</th>
            <th>Skor</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($player_logs as $idx => $log)
          <tr class="align-middle">
            <td>{{$log->created_at}}</td>
            <td>{{$log->player_msisdn}}</td>
            <td>{{$log->player_name}}</td>
            <td>{{$log->level_name}}</td>
            <td>{{$log->player_coin}}</td>
            <td>{{date("i:s", $log->time / 1000)}}</td>
            <td class="{{$log->score > 0 ? 'text-success' : 'text-danger'}}">
              {{$log->score > 0 ? '+'.$log->score : $log->score}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      {{ $player_logs->links('layouts.pagination') }}

    </div>
  </div>
</div>

<div class="modal fade" id="modal-status" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Status</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-status-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="handleCloseModal()">Tutup</button>
        <button type="button" class="btn btn-secondary" onclick="handleCloseModal()">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const modal = new bootstrap.Modal(document.getElementById('modal-status'))

  const handleToggleModal = (player) => {
    document.getElementById('modal-status-body').innerHTML = `<p>Anda yakin ingin mengubah status pemain <b>${player.name}</b> menjadi <b>${player.status === '1' ? 'Banned' : 'Active'}</b>?</p>`
    console.log(player)
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }

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
