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
      <a class="text-decoration-none text-black" href={{base_url('/terms')}}>User Dashboard</a>
    </li>
    @if(isset($user->id))
    <li class="breadcrumb-item active">Update User Dashboard</li>
    @else
    <li class="breadcrumb-item active">Buat Baru</li>
    @endif
  </ol>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-user me-1"></i>
      @if(isset($user->id))
      Update User Dashboard
      @else
      Buat User Dashboard Baru
      @endif
    </div>
    <div class="card-body">
      <form autocomplete="off" method="POST" action="{{$action_url}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" placeholder="Nama Lengkap" value="{{ isset($user->name) ? $user->name : old('name') }}" name="name">
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="Email" value="{{ isset($user->email) ? $user->email : old('email') }}" name="email">
          @error('description')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Role</label>
          <select type="text" class="form-control" placeholder="Nama" name="role_id">
            <option selected disabled>
              Pilih Role
            </option>
            @foreach($roles as $role)
            <option @if(isset($user->role_id)) {{$user->role_id == $role->id ? 'selected' : ''}} @endif value="{{$role->id}}">
              {{$role->name}}
            </option>
            @endforeach
          </select>
          @error('role_id')
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
    const user = @json($user)

    const innerHtml = user.id ?  `<p>Anda yakin ingin mengupdate user dashboard?</p>` :  `<p>Anda yakin ingin menyimpan user dashboard baru?</p>`
    document.getElementById('modal-status-body').innerHTML = innerHtml
    modal.toggle()
  }

  const handleCloseModal = () => {
    modal.hide()
  }
</script>
@endsection
