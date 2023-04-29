@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Pembelian Koin</li>
  </ol>

  @if (session('success_message'))
    <div class="alert alert-secondary alert-dismissible fade show">
      {{ session('success_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-server me-1"></i>
      Daftar Pembelian Koin
      <div class="ms-auto me-0 d-flex gap-2">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-search"></i>
          </span>
          <input type="text" class="form-control" placeholder="Nama / No. Handphone" aria-label="Name" onkeypress="handleSearch(event)" id="search-input" value="{{ $search }}">
        </div>
        <button class="btn btn-sm btn-secondary" onclick="handleSearchButton(event)">Cari</button>
        <button class="btn btn-sm btn-secondary" onclick="handleResetButton(event)">Reset</button>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-2">
        {{-- <a class="btn btn-sm btn-secondary" href="{{base_url('/coin-purchase/create')}}">Buat baru</a> --}}
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>No. Invoice</th>
            <th>No. Handphone</th>
            <th>Nama</th>
            <th>Pembayaran Via</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($coin_purchases as $idx => $coin_purchase)
          <tr class="align-middle">
            <td>{{$coin_purchase->created_at}}</td>
            <td>{{$coin_purchase->invoice_no}}</td>
            <td>{{$coin_purchase->msisdn}}</td>
            <td>{{$coin_purchase->player_name}}</td>
            <td>{{$coin_purchase->channel}}</td>
            <td>Rp{{number_format($coin_purchase->amount)}}</td>
            <td>
              {{$coin_purchase->status != "pending" ? 'Terbayar' : 'Pending'}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      {{ $coin_purchases->links('layouts.pagination') }}

    </div>
  </div>
</div>

<div class="modal fade" id="modal-status" tabindex="-1" aria-hidden="true">
  <div class="modal-diacoin_purchase modal-diacoin_purchase-centered">
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
    console.coin_purchase(player)
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
    window.location.href = url
  }

  const handleResetButton = (e) => {
    const cleanUrl = window.location.href.split('?')[0]
    const url = new URL(cleanUrl)
    window.location.href = url
  }
</script>
@endsection
