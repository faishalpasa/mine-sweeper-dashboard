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
      <a class="text-decoration-none text-black" href={{base_url('/period')}}>Syarat dan Ketentuan</a>
    </li>
    @if(isset($period['id']))
    <li class="breadcrumb-item active">Update Syarat dan Ketentuan</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-users me-1"></i>
      @if(isset($period['id']))
      Update Syarat dan Ketentuan
      @else
      Buat Syarat dan Ketentuan Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Judul</label>
          <input type="text" class="form-control" placeholder="Judul" value="{{ old('title') ?? $period['title'] }}" name="title">
          @error('title')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-2">
          <label for="name" class="form-label">Mulai</label>
          <input type="date" class="form-control" placeholder="Mulai" value="{{ old('start_at') ?? $period['start_at'] }}" name="start_at">
          @error('start_at')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-2">
          <label for="name" class="form-label">Berakhir</label>
          <input type="date" class="form-control" placeholder="Berakhir" value="{{ old('end_at') ?? $period['end_at'] }}" name="end_at">
          @error('end_at')
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
    const player = @json($period)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate periode?</p>` :  `<p>Anda yakin ingin menyimpan metode periode baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }
</script>
@endsection
