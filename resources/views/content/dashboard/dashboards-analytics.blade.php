@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

{{--@section('page-script')--}}
{{--<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>--}}
{{--@endsection--}}
<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title></title>
</head>>
@section('content')
<div class="row">

  <!-- Total Revenue -->
  <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
    <div class="card">
      <div class="row row-bordered g-0">
        <div class="col-md-14">
          <h5 class="card-header m-0 me-2 pb-3">Menu Perfomance Analysis</h5>
          <span>
          {!! $chart->container() !!}
            {!! $chart->script() !!}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
{{--<div style="width: 80%; margin: auto;">--}}
{{--  <canvas id="lineChart"></canvas>--}}
{{--</div>--}}

{{--<script>--}}
{{--  var ctx = document.getElementById('lineChart').getContext('2d');--}}
{{--  var myChart = new Chart(ctx, {--}}
{{--    type: 'line',--}}
{{--    data: {--}}
{{--      labels: @json($data['labels']),--}}
{{--      datasets: [{--}}
{{--        label: 'Data',--}}
{{--        data: @json($data['data']),--}}
{{--        borderColor: 'rgba(75, 192, 192, 1)',--}}
{{--        borderWidth: 1,--}}
{{--        fill: false--}}
{{--      }]--}}
{{--    },--}}
{{--    options: {--}}
{{--      scales: {--}}
{{--        y: {--}}
{{--          beginAtZero: true--}}
{{--        }--}}
{{--      }--}}
{{--    }--}}
{{--  });--}}
{{--</script>--}}
@endsection
