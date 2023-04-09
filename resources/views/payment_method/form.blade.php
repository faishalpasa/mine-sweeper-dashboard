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
      <a class="text-decoration-none text-black" href={{base_url('/payment-method')}}>Metode Pembayaran</a>
    </li>
    @if(isset($payment_method->id))
    <li class="breadcrumb-item active">Update Metode Pembayaran</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-money-bill-wave me-1"></i>
      @if(isset($payment_method->id))
      Update Metode Pembayaran
      @else
      Buat Metode Pembayaran Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama</label>
          <input type="text" class="form-control" placeholder="Nama" value="{{ isset($payment_method->name) ? $payment_method->name : old('name')  }}" name="name">
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">No. Akun</label>
          <input type="text" class="form-control" placeholder="081234567890" value="{{ isset($payment_method->account) ? $payment_method->account : old('account')  }}" name="account">
          @error('account')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Logo</label>
          <input type="file" class="form-control" placeholder="" value="{{ isset($payment_method->image_url) ? $payment_method->image_url : old('image_url') }}" name="image_url" onchange="handleChangeImage(event)" accept="image/*">
          <img id="image-preview" class="mt-3" style="width: 150px;" src="{{ isset($payment_method->image_url) ? '/files/'.$payment_method->image_url : ''}}" />
          @error('image_url')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        @if(isset($payment_method->id))
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Status</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_active" value="1" {{$payment_method->is_active == 1 ? 'checked' : ''}}>
            <label class="form-check-label">
              Aktif
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="is_active" value="0" {{$payment_method->is_active == 0 ? 'checked' : ''}}>
            <label class="form-check-label">
              Tidak aktif
            </label>
          </div>
        </div>
        @endif
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
    const player = @json($payment_method)

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
