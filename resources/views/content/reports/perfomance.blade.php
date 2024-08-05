@extends('layouts/contentNavbarLayout')

<head>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title></title>
</head>>
@section('title', 'Perfomance Report')

@section('content')

  <div class="card-body">
    <div class="row">
      <div class="mb-3 col-md-4">
        <label for="fromDate" class="form-label">From</label>
        <input class="form-control" type="date" name="fromDate" id="fromDate"/>
      </div>
      <div class="mb-3 col-md-4">
        <label for="toDate" class="form-label">To</label>
        <input class="form-control" type="date" name="toDate" id="toDate"/>
      </div>
      <div class="mb-3 col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2">Fetch Report</button>
      </div>
    </div>
    <div>
      <span>
           <h5 class="card-header m-0 me-2 pb-3">Menu Perfomance Analysis</h5>
      {!! $chart->container() !!}
        {!! $chart->script() !!}
      </span>



      <div class="mb-3">
        <label for="grossSales" class="form-label">Gross Sales (GBP)</label>
        <input class="form-control" type="text" id="grossSales" placeholder="Readonly input here..." readonly="" value="{{$grossSales}}">
      </div>

      <div class="mb-3">
        <label for="ordersDelivered" class="form-label">Orders Delivered</label>
        <input class="form-control" type="text" id="ordersDelivered" placeholder="Readonly input here..." readonly="" value="{{$ordersDelivered}}">
      </div>
      <div class="mb-3">
        <label for="aov" class="form-label">Average Order Value (GBP)</label>
        <input class="form-control" type="text" id="aov" placeholder="Readonly input here..." readonly="" value="{{$aov}}">
      </div>

      </div>
  </div>

@endsection
