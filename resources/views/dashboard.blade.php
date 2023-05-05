@extends('layouts.app')

@section('meta')
<title>Minesweeper Admin</title>
<meta name="description" content="Minesweeper Admin" />
@endsection

@section('style')
<style>
  .small-text {
    font-size: 10px;
  }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
  <div class="row">
    <div class="col-xl-3 col-md-6">
      <div class="card bg-secondary text-white mb-4">
        <div class="card-body">
          <p>Total Pemain</p>
          <p class="h1">{{number_format($total_players)}}</p>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-white stretched-link" href="{{base_url('/player')}}">Lihat Selengkapnya</a>
          <div class="small text-white"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-secondary text-white mb-4">
        <div class="card-body">
          <p>Total Pembelian Koin</p>
          <p class="h1">{{number_format($total_coin_purchases)}}</p>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-white stretched-link" href="{{base_url('/coin-purchase')}}">Lihat Selengkapnya</a>
          <div class="small text-white"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-secondary text-white mb-4">
        <div class="card-body">
          <p>Pembelian Koin <span class="small-text">(Periode Ini)</span></p>
          <p class="h1">{{$coin_purchases_per_period}}</p>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-white stretched-link" style="text-decoration: none">&nbsp;</a>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="card bg-secondary text-white mb-4">
        <div class="card-body">
          <p>Total Pendapatan <span class="small-text">(Estimasi)</span></p>
          <p class="h1">Rp{{number_format($total_revenue)}}</p>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-white stretched-link" style="text-decoration: none">&nbsp;</a>
        </div>
      </div>
    </div>
  </div>
  {{-- <div class="row">
    <div class="col-xl-6">
      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-chart-area me-1"></i>
          Pemain Mendaftar
        </div>
        <div class="card-body"><canvas id="player-chart" width="100%" height="40"></canvas></div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="card mb-4">
        <div class="card-header">
          <i class="fas fa-chart-bar me-1"></i>
          Total Transaksi Koin
        </div>
        <div class="card-body"><canvas id="coin-chart" width="100%" height="40"></canvas></div>
      </div>
    </div>
  </div> --}}
  <div class="card mb-4">
    <div class="card-header">
      <i class="fas fa-crown me-1"></i>
      Top Skor Periode Ini
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No. Handphone</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Level</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($top_scores as $top_scores)
            <tr class="align-middle">
              <td>{{$top_scores->player_msisdn}}</td>
              <td>{{$top_scores->player_name}}</td>
              <td>{{$top_scores->player_email}}</td>
              <td>{{$top_scores->max_level}}</td>
              <td>{{number_format($top_scores->total_score)}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">
      <i class="fas fa-coins me-1"></i>
      Pembelian Koin Terbaru
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>No. Invoice</th>
              <th>No. Handphone</th>
              <th>Nama</th>
              <th>Pembayaran Via</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($coin_purchases as $idx => $coin_purchase)
            <tr class="align-middle">
              <td>{{$coin_purchase->created_at}}</td>
              <td>{{$coin_purchase->invoice_no}}</td>
              <td>{{$coin_purchase->msisdn}}</td>
              <td>{{$coin_purchase->player_name}}</td>
              <td>{{$coin_purchase->channel}}</td>
              <td>Rp{{number_format($coin_purchase->amount)}}</td>
              <td>
                {{$coin_purchase->status != "pending" ? 'Terbayar' : 'Pending'}}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('script')
{{-- <script>

  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif'
  Chart.defaults.global.defaultFontColor = '#292b2c'

  const registeredPlayers = @json($registered_players)

  const playerChartElement = document.getElementById('player-chart')
  const playerChart = new Chart(playerChartElement, {
    type: 'line',
    data: {
      labels: registeredPlayers.map(player => player.date),
      datasets: [{
        label: "Pemain Mendaftar",
        lineTension: 0.3,
        backgroundColor: "rgba(2,117,216,0.2)",
        borderColor: "rgba(2,117,216,1)",
        pointRadius: 5,
        pointBackgroundColor: "rgba(2,117,216,1)",
        pointBorderColor: "rgba(255,255,255,0.8)",
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(2,117,216,1)",
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: registeredPlayers.map(player => player.total),
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 7
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            maxTicksLimit: 5
          },
          gridLines: {
            color: "rgba(0, 0, 0, .125)",
          }
        }],
      },
      legend: {
        display: false
      }
    }
  })

  const coinPurchases = @json($coin_purchases)

  const coinChartElement = document.getElementById('coin-chart');
  const coinChart = new Chart(coinChartElement, {
    type: 'line',
    data: {
      labels: coinPurchases.map(purchase => purchase.date),
      datasets: [{
        label: "Pembelian Koin",
        lineTension: 0.3,
        backgroundColor: "rgba(2,117,216,0.2)",
        borderColor: "rgba(2,117,216,1)",
        pointRadius: 5,
        pointBackgroundColor: "rgba(2,117,216,1)",
        pointBorderColor: "rgba(255,255,255,0.8)",
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(2,117,216,1)",
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: coinPurchases.map(purchase => purchase.total),
      }],
    },
    options: {
      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 7
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            maxTicksLimit: 5
          },
          gridLines: {
            color: "rgba(0, 0, 0, .125)",
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });
</script> --}}
@endsection
