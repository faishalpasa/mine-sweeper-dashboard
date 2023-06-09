<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('meta')

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="{{ base_url('/css/styles.css') }}" rel="stylesheet" />

    @yield('style')

  </head>
  <body class="sb-nav-fixed">
    @include('layouts.navigation')
    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        @include('layouts.sidebar')
      </div>
      <div id="layoutSidenav_content">
        <main>
          @yield('content')
        </main>
      </div>
    </div>

    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ base_url('/js/scripts.js') }}"></script>
    <script src="{{ base_url('/js/axios.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    @yield('script')
  </body>
</html>
