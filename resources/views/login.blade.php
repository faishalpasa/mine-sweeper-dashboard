<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="" />
    <title>Minesweeper Admin | Login</title>
    <meta name="description" content="Minesweeper Admin | Login" />
    <link href="{{base_url('/css/styles.css')}}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <style>
      .login-card {
        width: 100%;
        max-width: 420px;
      }
    </style>
  </head>
  <body class="bg-secondary">
    <div class="row justify-content-center flex-column h-100 align-items-center m-4">
      <div class="col-lg-12 login-card">
        <div class="card shadow-lg border-0 rounded-lg">
          <div class="card-header">
            <h1 class="text-center font-weight-light h3">Minesweeper Admin</h1>
            <h2 class="text-center font-weight-light h5">Login</h2>
          </div>
          <div class="card-body">
            @if (session('error_message'))
              <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form autocomplete="off" method="post" action="{{base_url('/login')}}">
              @csrf
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" placeholder="name@example.com" name="email" value="{{ old('email') }}" />
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <input class="form-control" type="password" placeholder="Password" name="password" id="password" />
                  <button class="btn btn-outline-secondary" type="button" id="password-button" onclick="handleClickPasswordButton()" id="password-button">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                <button type="submit" class="btn btn-secondary">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{base_url('js/scripts.js')}}"></script>
    <script>
      const inputPasswordElement = document.getElementById('password')
      const inputPasswordButtonElement = document.getElementById('password-button')

      let isPasswordRevealed = false
      const passwordIconRevealed = `<i class="fas fa-eye-slash" id="password-icon"></i>`;
      const passwordIcon = `<i class="fas fa-eye" id="password-icon"></i>`;

      const handleClickPasswordButton = (type) => {
        isPasswordRevealed = !isPasswordRevealed
        if (isPasswordRevealed) {
          inputPasswordElement.type = 'text'
          inputPasswordButtonElement.innerHTML = passwordIconRevealed
        } else {
          inputPasswordElement.type = 'password'
          inputPasswordButtonElement.innerHTML = passwordIcon
        }
      }
    </script>
  </body>
</html>
