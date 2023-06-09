@php
$auth = \App\Http\Controllers\WebControllers\UserController::get_auth();
@endphp

<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
  <div class="sb-sidenav-menu">
    <div class="nav">
      <div class="sb-sidenav-menu-heading"></div>
      <a class="nav-link" href="{{ base_url('/') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
        Dashboard
      </a>

      <div class="sb-sidenav-menu-heading">Permainan</div>
      <a class="nav-link" href="{{ base_url('/player') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
        Pemain
      </a>
      <a class="nav-link" href="{{ base_url('/top-score') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-crown"></i></div>
        Top Skor
      </a>
      <a class="nav-link" href="{{ base_url('/winner') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
        Pemenang
      </a>
      <a class="nav-link" href="{{ base_url('/player-log') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-server"></i></div>
        Log Permainan
      </a>

      <div class="sb-sidenav-menu-heading">Customer Care</div>
      <a class="nav-link" href="{{ base_url('/message') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-message"></i></div>
        Pesan
      </a>
      <a class="nav-link" href="{{ base_url('/coin-purchase') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-coins"></i></div>
        Pembelian Koin
      </a>

      <div class="sb-sidenav-menu-heading">Pengaturan</div>
      <a class="nav-link" href="{{ base_url('/period') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
        Periode
      </a>
      <a class="nav-link" href="{{ base_url('/level') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-sort-numeric-up-alt"></i></div>
        Level
      </a>
      <a class="nav-link" href="{{ base_url('/prize') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-gift"></i></div>
        Hadiah
      </a>
      <a class="nav-link" href="{{ base_url('/terms') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
        Syarat & Ketentuan
      </a>
      @isset($auth->role_id)
      @if($auth->role_id == 1)
      <div class="sb-sidenav-menu-heading">Admin Area</div>

      <a class="nav-link" href="{{ base_url('/user') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
        User Dashboard
      </a>
      @endif
      @endisset
      {{-- <a class="nav-link" href="{{ base_url('/payment-method') }}">
        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
        Metode Pembayaran
      </a> --}}
      {{-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
        Layouts
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
      </a>
      <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
        <nav class="sb-sidenav-menu-nested nav">
          <a class="nav-link" href="layout-static.html">Static Navigation</a>
          <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
        </nav>
      </div>
    </div> --}}
  </div>
</nav>
