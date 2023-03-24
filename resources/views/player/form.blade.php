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
      <a class="text-decoration-none text-black" href={{base_url('/player')}}>Pemain</a>
    </li>
    @if(isset($player['id']))
    <li class="breadcrumb-item active">Update Pemain</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-users me-1"></i>
      @if(isset($player['id']))
      Update Pemain
      @else
      Buat Pemain Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{isset($player['id']) ? base_url('/player/update/'.$player['id']) : base_url('/player/create')}}">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" placeholder="Nama Lengkap" value="{{ $errors->has('name') ? old('name') : $player['name'] }}" name="name">
          <div class="invalid-feedback" id="feedback-text-name"></div>
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="nama@email.com" value="{{ $errors->has('email') ? old('email') : $player['email'] }}" name="email">
          @error('email')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">No. Handphone</label>
          <input type="tel" class="form-control" placeholder="081234567890" value="{{ $errors->has('msisdn') ? old('msisdn') : $player['msisdn'] }}" name="msisdn">
          @error('msisdn')
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
    const player = @json($player)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate pemain?</p>` :  `<p>Anda yakin ingin menyimpan pemain baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
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
