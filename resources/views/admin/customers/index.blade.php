@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Manage Customers</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Manage Customers</li>
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
            <table class="table-hover table-striped text-nowrap table-sm" id="customer-table">
              <thead>
                <tr>
                  <th></th>
                  <th>Fullname</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Billing Address</th>
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
@endsection

@section('page-js')
<script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/DataTables-1.10.12/extensions/Pagination/input.js') }}"></script>
<script>
var customerTable;
$(document).ready(function () {
  customerTable = $('#customer-table').DataTable({
    processing: true,
    serverSide: true,
    "pagingType": "input",
    ajax: {
      url: "{{ url('admin/customers/getcustomer') }}",
      type:'POST'
    },
    columns: [
      {
        width:'2%', searchable: false, orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }, className: "text-center"
      },
      { data: 'fullname', name: 'fullname', searchable: true, orderable: true, width:'15%' },
      { data: 'email', name: 'email', searchable: false, orderable: false, width:'15%' },
      { data: 'username', name: 'username', searchable: false, orderable: false, width:'15%' },
      { data: 'address', name: 'address', searchable: true, orderable: true, width:'30%' },
      { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center" },
    ]
  });
});
</script>
@endsection