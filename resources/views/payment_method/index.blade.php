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
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Metode Pembayaran</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-gift me-1"></i>
      Daftar Metode Pembayaran
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Logo</th>
            <th>No. Akun</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($payment_methods as $payment_method)
          <tr class="align-middle">
            <td>{{$payment_method['name']}}</td>
            <td><img src="{{$payment_method['image_url']}}" class="image-prize" /></td>
            <td>{{$payment_method['account_no']}}</td>
            <td>{{$payment_method['is_active'] > 0 ? 'Aktif' : 'Tidak Aktif'}}</td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary" onclick="handleClickEditButton({{json_encode($payment_method)}})">
                  Edit
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
@endsection

@section('script')
<script>
  const handleClickEditButton = (prize) => {
    const url = new URL(`${window.location.href}/edit/${prize.id}`)
    window.location.href = url
  }
</script>
@endsection
