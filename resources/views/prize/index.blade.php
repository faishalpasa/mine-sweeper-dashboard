@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
  .image-prize {
    width: 150px;
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
  <ol class="breadcrumb my-4">
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item active">Hadiah</li>
  </ol>
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center">
      <i class="fas fa-gift me-1"></i>
      Daftar Hadiah
      <div class="ms-auto me-0">
        <div class="input-group input-group-sm">
          <span class="input-group-text">
            <i class="fas fa-calendar"></i>
          </span>
          <select class="form-select" onchange="handleChangePeriod(event)">
            @foreach ($periods as $idx => $period)
              <option {{$idx === 0 ? 'selected' : ''}} value="{{$period['id']}}">{{$period['label']}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Peringkat</th>
            <th>Nama</th>
            <th>Gambar</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($prizes as $prize)
          <tr class="align-middle">
            <td>{{$prize['rank']}}</td>
            <td>{{$prize['name']}}</td>
            <td><img src="{{$prize['image_url']}}" class="image-prize" /></td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary" onclick="handleClickEditButton({{json_encode($prize)}})">
                  Edit
                </button>
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
