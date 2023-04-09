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
      <a class="text-decoration-none text-black" href={{base_url('/payment-method')}}>Hadiah</a>
    </li>
    @if(isset($prize->id))
    <li class="breadcrumb-item active">Update Hadiah</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-gift me-1"></i>
      @if(isset($prize->id))
      Update Hadiah
      @else
      Buat Hadiah Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Periode</label>
          <div class="input-group">
            <select class="form-select" name="period">
              <option disabled selected>Pilih Periode</option>
              <option value="01" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '01' ? 'selected' : ''}} @endif>Januari</option>
              <option value="02" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '02' ? 'selected' : ''}} @endif>Februari</option>
              <option value="03" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '03' ? 'selected' : ''}} @endif>Maret</option>
              <option value="04" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '04' ? 'selected' : ''}} @endif>April</option>
              <option value="05" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '05' ? 'selected' : ''}} @endif>Mei</option>
              <option value="06" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '06' ? 'selected' : ''}} @endif>Juni</option>
              <option value="07" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '07' ? 'selected' : ''}} @endif>Juli</option>
              <option value="08" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '08' ? 'selected' : ''}} @endif>Agustus</option>
              <option value="09" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '09' ? 'selected' : ''}} @endif>September</option>
              <option value="10" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '10' ? 'selected' : ''}} @endif>Oktober</option>
              <option value="11" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '11' ? 'selected' : ''}} @endif>November</option>
              <option value="12" @if(isset($prize->period)) {{explode('-', $prize->period)[1] == '12' ? 'selected' : ''}} @endif>Desember</option>
            </select>
            <span class="input-group-text">{{date('Y')}}</span>
          </div>
          @error('period')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Peringkat</label>
          <select class="form-select" name="rank">
            <option disabled selected>Pilih Peringkat</option>
            <option value="1" @if(isset($prize->rank)) {{$prize->rank == '1' ? 'selected' : ''}} @endif>Peringkat 1</option>
            <option value="2" @if(isset($prize->rank)) {{$prize->rank == '2' ? 'selected' : ''}} @endif>Peringkat 2</option>
            <option value="3" @if(isset($prize->rank)) {{$prize->rank == '3' ? 'selected' : ''}} @endif>Peringkat 3</option>
          </select>
          @error('rank')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama Hadiah</label>
          <input type="text" class="form-control" placeholder="Nama" value="{{ isset($prize->name) ? $prize->name : old('name')  }}" name="name">
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label class="form-label">Gambar Hadiah</label>
          <input type="file" class="form-control" placeholder="" value="{{ isset($prize->image_url) ? $prize->image_url : old('image_url') }}" name="image_url" onchange="handleChangeImage(event)" accept="image/*">
          <img id="image-preview" class="mt-3" style="width: 150px;" src="{{ isset($prize->image_url) ? '/files/'.$prize->image_url : ''}}" />
          @error('image_url')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <button type="button" class="btn btn-secondary" onclick="handleToggleModal()">Simpan</button>
        </div>

        <div class="modal fade" id="modal-confirmation" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="modal-confirmation-body">
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
  const modal = new bootstrap.Modal(document.getElementById('modal-confirmation'))

  const handleToggleModal = () => {
    const player = @json($prize)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate hadiah?</p>` :  `<p>Anda yakin ingin menyimpan hadiah baru?</p>`
    document.getElementById('modal-confirmation-body').innerHTML = innerHtml
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
