@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
  .image-prize {
    width: 150px;
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Hadiah</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <div class="d-none d-sm-flex align-items-center">
        <i class="fas fa-gift me-1"></i>
        Daftar Hadiah
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
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/prize/create')}}">Buat baru</a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Peringkat</th>
              <th>Nama Hadiah</th>
              <th>Gambar Hadiah</th>
              <th style="width: 100px;"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($prizes as $prize)
            <tr class="align-middle">
              <td>{{$prize->rank}}</td>
              <td>{{$prize->name}}</td>
              <td><img src="/files/{{$prize->image_url}}" class="image-prize" /></td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a class="btn btn-outline-secondary" href="{{base_url('/prize/update/'.$prize->id)}}">
                    Edit
                  </a>
                  <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModalDelete({{json_encode($prize)}})">
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-status-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" onclick="handleCloseModal()">Tutup</button>
        <button type="button" class="btn btn-secondary" id="delete-button" onclick="handleClickDeleteButton(this)">Hapus</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const modalDelete = new bootstrap.Modal(document.getElementById('modal-delete'))

  const handleToggleModalDelete = (data) => {
    document.getElementById('modal-status-body').innerHTML = `<p>Anda yakin ingin menghapus <b>${data.name}</b>?`
    document.getElementById('delete-button').setAttribute('data-id', data.id)

    modalDelete.toggle()
  }

  const handleCloseModal = () => {
    modalDelete.hide()
  }

  const handleClickDeleteButton = (e) => {
    const id = e.dataset.id
    const url = new URL(`${window.location.href}/delete/${id}`)
    window.location.href = url.toString()
  }

  const handleChangePeriod = (e) => {
    const url = new URL(window.location.href)
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
