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
        <input class="form-control" type="datetime-local" name="fromDate" id="fromDate" value="{{ old('fromDate', request('fromDate')) }}"/>
      </div>
      <div class="mb-3 col-md-4">
        <label for="toDate" class="form-label">To</label>
        <input class="form-control" type="datetime-local" name="toDate" id="toDate" value="{{ old('toDate', request('toDate')) }}"/>
      </div>
      <div class="mb-3 col-md-4 d-flex align-items-end">
        <button type="submit" id="fetchReport" class="btn btn-primary me-2">Fetch Report</button>
      </div>
    </div>
    <div>
      <div class="card">
        <h5 class="card-header">Availability Report</h5>
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
            <tr>
              <th>Open Rate</th>
              <th>Total Rejected Orders</th>
              <th>Percentage of Rejected Orders</th>
              <th>Order Value of Rejected Orders</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            <tr>
              <td>{{$openRate}}</td>
              <td>{{$totalRejectedOrdersCount}}</td>
              <td>{{$ordersRejectedPercentage}}</td>
              <td>£{{$orderValueRejectedOrders}}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <BR>
    <div>
            <span>
                 <h5 class="card-header m-0 me-2 pb-3">Bar Chart</h5>
            {!! $chart->container() !!}
              {!! $chart->script() !!}
            </span>
    </div>
  </div>
  <script>
    document.getElementById('fetchReport').addEventListener('click', function () {
      var fromDate = document.getElementById('fromDate').value;
      var toDate = document.getElementById('toDate').value;

      if (fromDate && toDate) {
        var url = new URL(window.location.href);
        url.searchParams.set('fromDate', fromDate);
        url.searchParams.set('toDate', toDate);
        window.location.href = url.toString();
      } else {
        alert('Please select both start and end dates.');
      }
    });
  </script>
@endsection
