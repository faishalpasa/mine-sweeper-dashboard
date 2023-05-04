@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Level</li>
  </ol>

  @if (session('success_message'))
    <div class="alert alert-secondary alert-dismissible fade show">
      {{ session('success_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <div class="d-none d-sm-flex align-items-center">
        <i class="fas fa-sort-numeric-up-alt"></i>&nbsp;
        Daftar Level
      </div>
    </div>
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/level/create')}}">Buat baru</a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Kolom</th>
              <th>Baris</th>
              <th>Jumlah Bom</th>
              <th style="width: 100px;"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($levels as $level)
            <tr class="align-middle">
              <td>Level {{$level->name}}</td>
              <td>{{$level->cols}}</td>
              <td>{{$level->rows}}</td>
              <td>{{$level->mines}}</td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a type="button" class="btn btn-outline-secondary" href="{{base_url('/level/update/'.$level->id)}}">
                    Edit
                  </a>
                  <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModalDelete({{json_encode($level)}})">
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

  const handleToggleModalDelete = (term) => {
    document.getElementById('modal-status-body').innerHTML = `<p>Anda yakin ingin menghapus <b>Level ${term.name}</b>?`
    document.getElementById('delete-button').setAttribute('data-term-id', term.id)

    modalDelete.toggle()
  }

  const handleCloseModal = () => {
    modalDelete.hide()
  }

  const handleClickDeleteButton = (e) => {
    const id = e.dataset.termId
    const url = new URL(`${window.location.href}/delete/${id}`)
    window.location.href = url.toString()
  }
</script>
@endsection
