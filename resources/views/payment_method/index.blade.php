@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
  .image-prize {
    width: 50px;
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">
      <a class="text-decoration-none text-black" href={{base_url('/')}}>Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Metode Pembayaran</li>
  </ol>

  @if (session('success_message'))
    <div class="alert alert-secondary alert-dismissible fade show">
      {{ session('success_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-gift me-1"></i>
      Daftar Metode Pembayaran
    </div>
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/payment-method/create')}}">Buat baru</a>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Logo</th>
            <th>No. Akun</th>
            <th>Status</th>
            <th style="width: 100px;"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($payment_methods as $payment_method)
          <tr class="align-middle">
            <td>{{$payment_method->name}}</td>
            <td><img src="/files/{{$payment_method->image_url}}" class="image-prize" /></td>
            <td>{{$payment_method->account}}</td>
            <td>{{$payment_method->is_active > 0 ? 'Aktif' : 'Tidak Aktif'}}</td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <a class="btn btn-outline-secondary" href="{{base_url('/payment-method/update/'.$payment_method->id)}}">
                  Edit
                </a>
                <button type="button" class="btn btn-outline-secondary" onclick="handleToggleModalDelete({{json_encode($payment_method)}})">
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
</script>
@endsection
