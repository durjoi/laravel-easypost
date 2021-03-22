@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Manage Orders</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Manage Orders</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <table class="table-hover table-striped text-nowrap table-sm" id="order-table">
              <thead>
                <tr>
                  <th></th>
                  <th>Order #</th>
                  <th>Buyer</th>
                  <th>Tracking #</th>
                  <th>Total Cost</th>
                  <th>Status</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/datatables-keytable/css/keyTable.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.css') }}">
<style>
.text-right {
  text-align: right;
}
.align-top {
  vertical-align: top;
}
</style>
@endsection

@section('page-js')
<script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/DataTables-1.10.12/extensions/Pagination/input.js') }}"></script>
<script>
var orderTable;
$(document).ready(function () {
  orderTable = $('#order-table').DataTable({
    processing: true,
    serverSide: true,
    "pagingType": "input",
    ajax: {
      url: "{{ url('admin/orders/getorder') }}",
      type:'POST'
    },
    columns: [
      {
        width:'2%', searchable: false, orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }, className: "text-center align-top"
      },
      { data: 'order_number', name: 'order_number', searchable: true, orderable: true, width:'15%' },
      { data: 'buyer_name', name: 'buyer_name', searchable: false, orderable: false, width:'25%' },
      { data: 'tracking_number', name: 'tracking_number', searchable: false, orderable: false, width:'15%' },
      { data: 'total_cost', name: 'total_cost', searchable: true, orderable: false, width:'15%', className: "text-right align-top" },
      { data: 'status', name: 'status', searchable: true, orderable: false, width:'15%', className: "text-center align-top" },
      { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center" },
    ]
  });
});
</script>
@endsection