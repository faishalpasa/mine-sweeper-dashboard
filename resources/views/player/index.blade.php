@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Pemain</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-table me-1"></i>
      Daftar Pemain
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
            <th></th>
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
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModal({{json_encode($player)}})">
                  Ubah Status
                </button>
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
