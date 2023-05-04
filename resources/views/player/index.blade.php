@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin | Pemain</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">
      <a class="text-decoration-none text-black" href={{base_url('/')}}>Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Pemain</li>
  </ol>

  @if (session('success_message'))
    <div class="alert alert-secondary alert-dismissible fade show">
      {{ session('success_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (session('error_message'))
    <div class="alert alert-danger alert-dismissible fade show">
      {{ session('error_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <div class="d-none d-sm-flex align-items-center">
        <i class="fas fa-users me-1"></i>
        Daftar Pemain
      </div>
      <div class="ms-auto me-0 d-flex gap-2">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
          <input id="search-input" type="text" class="form-control" placeholder="No. Handphone" aria-label="Name" onkeypress="handleSearch(event)" value="{{ $search }}">
        </div>
        <button class="btn btn-sm btn-secondary" onclick="handleSearchButton(event)">Cari</button>
        <button class="btn btn-sm btn-secondary" onclick="handleResetButton(event)">Reset</button>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/player/create')}}">Buat baru</a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No. Handphone</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Jumlah Koin</th>
              <th>Status</th>
              <th style="width: 300px;"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($players as $idx => $player)
            <tr class="align-middle">
              <td>{{$player->msisdn}}</td>
              <td>{{$player->name}}</td>
              <td>{{$player->email}}</td>
              <td>{{$player->coin}}</td>
              <td>{{$player->status == 1 ? 'Active' : 'Banned'}}</td>
              <td class="d-flex justify-content-center">
                <div class="btn-group btn-group-sm" role="group">
                  <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModalStatus({{json_encode($player)}})">
                    Ubah Status
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModalCoin({{json_encode($player)}})">
                    Ubah Koin
                  </button>
                  <a class="btn btn-outline-secondary" href="{{base_url('/player/update/'.$player->id)}}">
                    Edit
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{ $players->links('layouts.pagination') }}

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
        <button type="button" class="btn" onclick="handleCloseModalStatus()">Tutup</button>
        <button type="button" class="btn btn-secondary" onclick="handleClickUpdateStatusButton(this)" id="update-status-button">Update</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-coin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Koin Pemain</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form autocomplete="off" method="POST" id="form-coin">
        <div class="modal-body" id="modal-coin-body">
          @csrf
          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">Jumlah Koin Lama</label>
            <input type="text" class="form-control" placeholder="Koin" id="old-coin" readonly>
          </div>

          <div class="mb-3 col-md-6">
            <label for="name" class="form-label">Jumlah Koin Baru</label>
            <input type="text" class="form-control" placeholder="Masukan jumlah koin" name="coin" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" onclick="handleCloseModal()">Tutup</button>
          <button type="submit" class="btn btn-secondary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('script')
<script>
  const modalStatus = new bootstrap.Modal(document.getElementById('modal-status'))
  const modalCoin = new bootstrap.Modal(document.getElementById('modal-coin'))

  const handleToggleModalStatus = (player) => {
    document.getElementById('modal-status-body').innerHTML = `<p>Anda yakin ingin mengubah status pemain <b>${player.name || player.msisdn}</b> menjadi <b>${player.status == 1 ? 'Banned' : 'Active'}</b>?</p>`
    document.getElementById('update-status-button').setAttribute('data-id', player.id)
    modalStatus.toggle()
  }

  const handleCloseModalStatus = () => {
    modalStatus.hide()
  }

  const handleClickUpdateStatusButton = (e) => {
    const id = e.dataset.id
    const url = new URL(`${window.location.href}/update-status/${id}`)
    window.location.href = url.toString()
  }

  const handleToggleModalCoin = (player) => {
    document.getElementById('old-coin').value = player.coin
    document.getElementById('form-coin').action = `/player/update-coin/${player.id}`
    document.getElementById('update-status-button').setAttribute('data-id', player.id)
    modalCoin.toggle()
  }

  const handleCloseModalCoin = () => {
    modalCoin.hide()
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
    window.location.href = url
  }

  const handleResetButton = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    window.location.href = url
  }
</script>
@endsection
