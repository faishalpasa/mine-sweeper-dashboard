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
    <li class="breadcrumb-item">
      <a class="text-decoration-none text-black" href={{base_url('/coin-purchase')}}>Pembelian Koin</a>
    </li>
    @if(isset($coin_purchase['id']))
    <li class="breadcrumb-item active">Update Metode Pembayaran</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-users me-1"></i>
      @if(isset($coin_purchase['id']))
      Update Pembelian Koin
      @else
      Buat Pembelian Koin Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama</label>
          <input type="text" class="form-control" placeholder="Nama" value="{{ old('name') ?? $coin_purchase['name'] }}" name="name">
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">No. Handphone</label>
          <input type="text" class="form-control" placeholder="081234567890" value="{{ old('msisdn') ?? $coin_purchase['msisdn'] }}" name="msisdn">
          @error('msisdn')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Total Koin</label>
          <select type="text" class="form-control" name="amount">
            <option selected disabled>Pilih jumlah koin</option>
            <option value="5000" {{old('amount') === '5000' ? 'selected' : ''}}>10 Koin / Rp5,000</option>
            <option value="10000" {{old('amount') === '5000' ? 'selected' : ''}}>25 Koin / Rp10,000</option>
          </select>
          @error('amount')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Metode Pembayaran</label>
          <select type="text" class="form-control" name="payment_method_id">
            <option selected disabled>Pilih metode pembayaran</option>
            @foreach($payment_methods as $payment_method)
              <option value="{{$payment_method['id']}}" {{old('payment_method_id') === $payment_method['id'] ? 'selected' : ''}}>
                {{$payment_method['name']}}
              </option>
            @endforeach
          </select>
          @error('payment_method_id')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <button type="button" class="btn btn-secondary" onclick="handleToggleModal()">Simpan</button>
        </div>

        <div class="modal fade" id="modal-status" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="modal-status-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn" onclick="handleCloseModal()">Tutup</button>
                <button type="submit" class="btn btn-secondary">Yakin</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('script')
<script>
  const modal = new bootstrap.Modal(document.getElementById('modal-status'))

  const handleToggleModal = () => {
    const player = @json($coin_purchase)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate metode pembayaran?</p>` :  `<p>Anda yakin ingin menyimpan metode pembayaran baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }

  const handleChangeImage = (e) => {
    const output = document.getElementById('image-preview');
    output.src = URL.createObjectURL(e.target.files[0]);
    output.onload = () => {
      URL.revokeObjectURL(output.src)
    }
  }
</script>
@endsection
