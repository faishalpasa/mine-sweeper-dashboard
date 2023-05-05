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
      <a class="text-decoration-none text-black" href={{base_url('/level')}}>Level</a>
    </li>
    @if(isset($level->id))
    <li class="breadcrumb-item active">Update Level</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-sort-numeric-up-alt"></i>&nbsp;
      @if(isset($level->id))
      Update Level
      @else
      Buat Level Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama</label>
          <select type="text" class="form-control" placeholder="Nama"  name="name">
            <option selected disabled>
              Pilih Level
            </option>
            @foreach($list_level as $list)
            <option @if(isset($level->name)) {{$level->name == $list ? 'selected' : ''}} @endif value="{{$list}}">
              Level {{$list}}
            </option>
            @endforeach
          </select>
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Jumlah Kolom</label>
          <input id="input-column" type="text" class="form-control" placeholder="Kolom" value="{{ isset($level->cols) ? $level->cols : old('cols') }}" name="cols" onkeyup="handleCalculateBombPerentage()">
          @error('cols')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Jumlah Baris</label>
          <input id="input-row" type="text" class="form-control" placeholder="Baris" value="{{ isset($level->rows) ? $level->rows : old('rows') }}" name="rows" onkeyup="handleCalculateBombPerentage()">
          @error('rows')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Jumlah Bom</label>
          <div class="d-flex gap-2">
            <div class="d-flex flex-column w-50">
              <input id="input-bomb" type="text" class="form-control" placeholder="Jumlah Bom" value="{{ isset($level->mines) ? $level->mines : old('mines') }}" name="mines" onkeyup="handleCalculateBombPerentage()">
              @error('mines')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="input-group w-50">
              <input disabled class="form-control" id="input-bom-percentage">
              <span class="input-group-text">
                %
              </span>
            </div>
          </div>
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

  const decimal = (value) => {
    return Math.round(value * 100) / 100
  }

  const handleCalculateBombPerentage = () => {
    const columnValue = document.getElementById('input-column').value
    const rowValue = document.getElementById('input-row').value
    const bombValue = document.getElementById('input-bomb').value
    const bombPercentage = bombValue / (columnValue * rowValue) * 100

    const bombPercentageValue = isFinite(bombPercentage) ? decimal(bombPercentage) : 0
    document.getElementById('input-bom-percentage').value = bombPercentageValue
  }

  handleCalculateBombPerentage()

  const handleToggleModal = () => {
    const player = @json($level)

    const innerHtml = player.id ?  `<p>Anda yakin ingin mengupdate level?</p>` :  `<p>Anda yakin ingin menyimpan level baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }
</script>
@endsection
