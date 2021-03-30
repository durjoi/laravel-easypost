@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Manage Users</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Manage Users</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-tools">
              <a href="{{ url('admin/settings/users/create') }}" class="btn btn-primary btn-sm">Create User</a>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-hover text-nowrap table-sm" id="user-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fullname</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
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
<script type="text/javascript">
var userTable;
  var baseUrl = $('body').attr('data-url');
$(document).ready(function() {
  userTable = $('#user-table').DataTable({
    processing: true,
    serverSide: true,
    "pagingType": "input",
    ajax: {
      url: "{{ url('api/settings/users') }}",
      type:'POST'
    },
    columns: [
      {
        width:'2%', searchable: false, orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      { data: 'name', name: 'name', searchable: true, orderable: true, width:'25%' },
      { data: 'email', name: 'email', searchable: true, orderable: true, width:'25%' },
      { data: 'status', name: 'status', searchable: false, orderable: false, width:'15%' },
      // { data: 'role', name: 'role', searchable: false, orderable: false, width:'15%' },
      { data: 'action', name: 'action', searchable: false, orderable: false, width:'20%', className: "text-center"},
    ],
    "order": [[1, "asc"]],
  });
});

function deleteuser(id){
    var form_url = baseUrl+'/admin/settings/users/'+id;
  doAjaxConfirmProcessing ('DELETE', '', {}, form_url)
  // if(confirm('Are you sure you want to delete this?')){
  //   $.ajax({
  //     type: "DELETE",
  //     url: "{{ url('admin/settings/users') }}/"+id,
  //     dataType: "json",
  //     success: function (response) {
  //         swalWarning ("Congratulations!", "User has been successfully deleted", "success", "Done");
  //         // return false;
  //       userTable.draw();
  //     }
  //   });
  // }
}
</script>
@endsection