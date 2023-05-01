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
      <a class="text-decoration-none text-black" href={{base_url('/terms')}}>Syarat dan Ketentuan</a>
    </li>
    @if(isset($terms->id))
    <li class="breadcrumb-item active">Update Syarat dan Ketentuan</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-book me-1"></i>
      @if(isset($terms->id))
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
          <input type="text" class="form-control" placeholder="Judul" value="{{ isset($terms->title) ? $terms->title : old('title') }}" name="title">
          @error('title')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Deskripsi</label>
          <textarea type="text" class="form-control" placeholder="Deskripsi" name="description" rows="4">{{ isset($terms->description) ? $terms->description : old('description') }}</textarea>
          @error('description')
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
    const player = @json($terms)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate syarat dan ketentuan?</p>` :  `<p>Anda yakin ingin menyimpan syarat dan ketentuan baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }
</script>
@endsection
