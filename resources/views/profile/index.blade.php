@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin | Profile</title>
<meta name="description" content="Minesweeper Admin | Profile" />
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item"><a class="text-black text-decoration-none" href="{{ base_url('/')}}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profil</li>
  </ol>
  <div class="card">
    <div class="card-body">
      @if (session('success_message'))
        <div class="alert alert-secondary alert-dismissible fade show">
          {{ session('success_message') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <form autocomplete="off" method="POST" action="{{base_url('/profile/update')}}">
        @csrf
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" placeholder="Nama Lengkap" value="{{ $errors->has('name') ? old('name') : $profile['name'] }}" name="name">
          <div class="invalid-feedback" id="feedback-text-name"></div>
          @error('name')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="nama@email.com" value="{{ $errors->has('email') ? old('email') : $profile['email'] }}" name="email">
          @error('email')
            <div class="invalid-feedback d-block">
              {{ $message }}
            </div>
          @enderror
        </div>
        <div class="mb-3 col-md-6">
          <input class="form-check-input" type="checkbox" value="" onclick="handleClickCheckbox(event.target.checked)" {{$errors->has('password') ? 'checked' : ''}}>
          <label class="form-check-label">
            Ubah password?
          </label>
        </div>
        <div id="password-form" class="d-none">
          <div class="mb-3 col-md-6">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="{{old('password')}}">
              <button class="btn btn-outline-secondary" type="button" id="password-button" onclick="handleClickPasswordButton('password')">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="mb-3 col-md-6">
            <label for="exampleFormControlInput1" class="form-label">Konfirmasi Password</label>
            <div class="input-group has-validation">
              <input type="password" class="form-control" id="password-confirmation" placeholder="Konfirmasi Password" name="password_confirmation" value="{{old('password_confirmation')}}">
              <button class="btn btn-outline-secondary" type="button" id="password-confirmation-button" onclick="handleClickPasswordButton('password-confirmation')">
                <i class="fas fa-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <button type="submit" class="btn btn-secondary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const inputPasswordElement = document.getElementById('password')
  const inputPasswordConfirmationElement = document.getElementById('password-confirmation')
  const passwordFormElement = document.getElementById('password-form')

  const inputPasswordButtonElement = document.getElementById('password-button')
  const inputPasswordConfirmationButtonElement = document.getElementById('password-confirmation-button')

  const handleClickCheckbox = (value) => {
    passwordFormElement.classList.add(value ? 'd-block' : 'd-none')
    passwordFormElement.classList.remove(value ? 'd-none' : 'd-block')
  }

  const isCheckedFromServer = {{$errors->has('password') ? $errors->has('password') : 0}}

  if (isCheckedFromServer) {
    handleClickCheckbox(true)
  }

  let isPasswordRevealed = false
  let isConfirmPasswordRevealed = false
  const passwordIconRevealed = `<i class="fas fa-eye-slash" id="password-icon"></i>`;
  const passwordIcon = `<i class="fas fa-eye" id="password-icon"></i>`;

  const handleClickPasswordButton = (type) => {
    if (type === 'password') {
      isPasswordRevealed = !isPasswordRevealed
      if (isPasswordRevealed) {
        inputPasswordElement.type = 'text'
        inputPasswordButtonElement.innerHTML = passwordIconRevealed
      } else {
        inputPasswordElement.type = 'password'
        inputPasswordButtonElement.innerHTML = passwordIcon
      }
    }

    if (type === 'password-confirmation') {
      isConfirmPasswordRevealed = !isConfirmPasswordRevealed
      if (isConfirmPasswordRevealed) {
        inputPasswordConfirmationElement.type = 'text'
        inputPasswordConfirmationButtonElement.innerHTML = passwordIconRevealed
      } else {
        inputPasswordConfirmationElement.type = 'password'
        inputPasswordConfirmationButtonElement.innerHTML = passwordIcon
      }
    }
  }
</script>
@endsection
