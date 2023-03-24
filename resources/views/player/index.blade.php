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

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-users me-1"></i>
      Daftar Pemain
      <div class="ms-2 me-0">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/player/create')}}">Buat baru</a>
      </div>
      <div class="ms-auto me-0">
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
            <th>Status</th>
            <th style="width: 200px;"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($players as $idx => $player)
          <tr class="align-middle">
            <td>{{$player['msisdn']}}</td>
            <td>{{$player['name']}}</td>
            <td>{{$player['email']}}</td>
            <td>{{$player['level']}}</td>
            <td>{{$player['status'] === '1' ? 'Active' : 'Banned'}}</td>
            <td class="d-flex justify-content-center">
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModal({{json_encode($player)}})">
                  Ubah Status
                </button>
                <a class="btn btn-outline-secondary" href="{{base_url('/player/update/'.$player['id'])}}">
                  Edit
                </a>
              </div>
            </td>
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
      window.location.href = `${window.location.href}?search=${e.target.value}`
    }
  }
</script>
@endsection
