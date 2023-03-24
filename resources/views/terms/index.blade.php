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
    <li class="breadcrumb-item active">Syarat dan Ketentuan</li>
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
      Daftar Syarat dan Ketentuan
    </div>
    <div class="card-body">
      <div class="mb-2">
        <a class="btn btn-sm btn-secondary" href="{{base_url('/terms/create')}}">Buat baru</a>
      </div>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Deskripsi</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($terms as $term)
          <tr class="align-middle">
            <td>{{$term['description']}}</td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <a type="button" class="btn btn-outline-secondary" href="{{base_url('/terms/update/'.$term['id'])}}">
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
  const handleClickEditButton = (prize) => {
    const url = new URL(`${window.location.href}/edit/${prize.id}`)
    window.location.href = url
  }
</script>
@endsection
