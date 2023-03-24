@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Periode</li>
  </ol>

  @if (session('success_message'))
    <div class="alert alert-secondary alert-dismissible fade show">
      {{ session('success_message') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-book me-1"></i>
      Daftar Periode
    </div>
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/period/create')}}">Buat baru</a>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Akhir</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($periods as $period)
          <tr class="align-middle">
            <td>{{$period['title']}}</td>
            <td>{{date_id($period['start_at'])}}</td>
            <td>{{date_id($period['end_at'])}}</td>
            <td>{{$period['status'] > 0 ? 'Aktif' : 'Berakhir'}}</td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-outline-secondary" href="{{base_url('/period/update/'.$period['id'])}}">
                  Edit
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
</script>
@endsection
