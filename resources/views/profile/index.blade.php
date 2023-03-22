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
      <form>
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" id="name" placeholder="Nama Lengkap" value="Administrator">
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" placeholder="nama@email.com" value="administrator@email.com">
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" placeholder="Password" onchange="handleChangePassword()">
            <button class="btn btn-outline-secondary" type="button" id="password-button" onclick="handleClickPasswordButton('password')">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <label for="exampleFormControlInput1" class="form-label">Konfirmasi Password</label>
          <div class="input-group has-validation">
            <input type="password" class="form-control" id="password-confirmation" placeholder="Konfirmasi Password" onchange="handleChangePassword()">
            <button class="btn btn-outline-secondary" type="button" id="password-confirmation-button" onclick="handleClickPasswordButton('password-confirmation')">
              <i class="fas fa-eye"></i>
            </button>
            <div class="invalid-feedback" id="password-confirmation-message">
              Password konfirmasi tidak sesuai
            </div>
          </div>
        </div>
        <div class="mb-3 col-md-6">
          <button type="button" class="btn btn-secondary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  const inputPasswordElement = document.getElementById('password')
  const inputPasswordButtonElement = document.getElementById('password-button')

  const inputPasswordConfirmationElement = document.getElementById('password-confirmation')
  const inputPasswordConfirmationButtonElement = document.getElementById('password-confirmation-button')

  const inputPasswordConfirmationMessageElement = document.getElementById('password-confirmation-message')

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

  const handleChangePassword = () => {
    const passwordValue = inputPasswordElement.value
    const passwordConfirmationValue = inputPasswordConfirmationElement.value

    if (passwordValue) {
      if (passwordValue === passwordConfirmationValue) {
        inputPasswordConfirmationMessageElement.classList.add('d-none')
        inputPasswordConfirmationMessageElement.classList.remove('d-block')
      } else {
        inputPasswordConfirmationMessageElement.classList.add('d-block')
        inputPasswordConfirmationMessageElement.classList.remove('d-none')
      }
    }
  }

</script>
@endsection
