@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Manage Products</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Manage Products</li>
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
            <div class="float-left">
              <h4 id="table-header">Product List</h4>
            </div>
            <div class="card-tools">
              <div>
                <!-- <div class="form-check form-check-inline">
                  <input class="form-check-input chk-type" name="type_device" type="checkbox" id="chk-buying" value="Buy" checked>
                  <label class="form-check-label" for="chk-buying">Buying</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input chk-type" name="type_device" type="checkbox" id="chk-selling" value="Sell" checked>
                  <label class="form-check-label" for="chk-selling">Selling</label>
                </div> -->
                <a class="btn btn-primary btn-sm" href="{{ url('admin/products/create') }}">Create Product</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-hover table-striped text-nowrap table-sm" id="product-table">
                <thead>
                  <tr>
                    <th></th>
                    <th class="text-center">Photo</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Other Info</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <input type="hidden" id="filtertype" value="Both">
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
@include('admin.modals.products.modal')
{!! JsValidator::formRequest('App\Http\Requests\Admin\ProductDupRequest') !!}
<script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ url('assets/plugins/DataTables-1.10.12/extensions/Pagination/input.js') }}"></script>

<script src="{{ url('library/js/admin/products/components.js') }}"></script>
<script type="text/javascript">
var productTable;
$(document).ready(function() {
  <?php if(session()->has('msg')){ ?>
  toastr.success('<?php echo session('msg'); ?>')
  <?php } ?>
  productTable = $('#product-table').DataTable({
    processing: true,
    serverSide: true,
    "pagingType": "input",
    ajax: {
      url: "{{ url('admin/products/getproduct') }}",
      type:'POST'
    },
    columns: [
      {
        width:'2%', searchable: false, orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }, className: "text-center"
      },
      { data: 'photo', name: 'photo', searchable: false, orderable: false, width:'10%', className: "text-center" },
      { data: 'brand', name: 'brand', searchable: true, orderable: true, width:'18%' },
      { data: 'model', name: 'model', searchable: true, orderable: true, width:'23%' },
      { data: 'color', name: 'color', searchable: false, orderable: false, width:'9%' }, 
      { data: 'otherInfo', name: 'otherInfo', searchable: false, orderable: false, width:'25%' },
      { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center" },
    ]
  });

  $('#chk-buying').change(function(){
    if($(this).prop('checked') == true && $('#chk-selling').prop('checked') == false){
      filter('Buy');
    } 
    if($(this).prop('checked') == true && $('#chk-selling').prop('checked') == true){
      filter('Both');
    }
    if($(this).prop('checked') == false && $('#chk-selling').prop('checked') == true){
      filter('Sell');
    } 
    if($(this).prop('checked') == false && $('#chk-selling').prop('checked') == false){
      filter('None');
    } 
  })
  $('#chk-selling').change(function(){
    if($(this).prop('checked') == true && $('#chk-buying').prop('checked') == false){
      filter('Sell');
    } 
    if($(this).prop('checked') == true && $('#chk-buying').prop('checked') == true){
      filter('Both');
    } 
    if($(this).prop('checked') == false && $('#chk-buying').prop('checked') == true){
      filter('Buy');
    } 
    if($(this).prop('checked') == false && $('#chk-buying').prop('checked') == false){
      filter('None');
    }
  });

  $('#excellent_offer').keyup(function(){
    if($('#offer_type').prop('checked')){
      var good = percent($('#excellent_offer').val(), 'good');
      var fair = percent($('#excellent_offer').val(), 'fair');
      var poor = percent($('#excellent_offer').val(), 'poor');
      $('#good_offer').val(good);
      $('#fair_offer').val(fair);
      $('#poor_offer').val(poor);
    }
  });

  $('#offer_type').change(function() {
    if($(this).prop('checked')){
      var good = percent($('#excellent_offer').val(), 'good');
      var fair = percent($('#excellent_offer').val(), 'fair');
      var poor = percent($('#excellent_offer').val(), 'poor');
      $('#good_offer').val(good);
      $('#fair_offer').val(fair);
      $('#poor_offer').val(poor);
    }
  });

  $('#product-form').submit(function(e){
    e.preventDefault();
    if($(this).valid()){
      var data = $(this).serializeArray();
      $.ajax({
        type: "POST",
        url: "{{ url('admin/products/storeduplicate') }}",
        data: data,
        dataType: "json",
        success: function (response) {
          if(response.response == 2){
            $('#exist-error').show();
          }
          if(response.response == 1){
            productTable.draw();
            $('#modal-product').modal('hide');
          }
          setInterval(function(){ $('#exist-error').fadeOut() }, 3000);
        }
      });
    }
  });
});

// function deleteproduct(id){
//   if(confirm('Are you sure you want to delete this?')){
//     $.ajax({
//       type: "DELETE",
//       url: "{{ url('admin/products') }}/"+id,
//       dataType: "json",
//       success: function (response) {
//         toastr.success('Product has been deleted!')
//         productTable.draw();
//       }
//     });
//   }
// }

function filter(type){
  if(type == 'Sell'){
    $('#table-header').html('Selling Product');
  } else if(type == 'Buy') {
    $('#table-header').html('Buying Product');
  } else if(type == 'None') {
    $('#table-header').html('');
  } else {
    $('#table-header').html('Both Selling and Buying');
  }
  $('#product-table').DataTable().destroy();
  $('#product-table').DataTable({
    processing: true,
    serverSide: true,
    "pagingType": "input",
    ajax: {
      url: "{{ url('admin/products/postproduct') }}",
      type:'POST',
      data: {
        device_type: type
      }
    },
    columns: [
      {
        width:'2%', searchable: false, orderable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }, className: "text-center"
      },
      { data: 'photo', name: 'photo', searchable: false, orderable: false, width:'10%', className: "text-center" },
      { data: 'brand', name: 'brand', searchable: true, orderable: true, width:'20%' },
      { data: 'model', name: 'model', searchable: true, orderable: true, width:'25%' },
      { data: 'type', name: 'type', searchable: false, orderable: false, width:'5%' },
      { data: 'otherInfo', name: 'otherInfo', searchable: false, orderable: false, width:'25%' },
      { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center" },

      // { data: 'photo', name: 'photo', searchable: false, orderable: false, width:'10%', className: "text-center" },
      // { data: 'brand', name: 'brand', searchable: true, orderable: true, width:'15%' },
      // { data: 'model', name: 'model', searchable: true, orderable: true, width:'25%' },
      // { data: 'type', name: 'type', searchable: false, orderable: false, width:'5%' },
      // { data: 'otherInfo', name: 'otherInfo', searchable: false, orderable: false, width:'20%' },
      // { data: 'action', name: 'action', searchable: false, orderable: false, width:'20%', className: "text-center" },
    ]
  });
}

function duplicate(id){
  $('#product_id').val(id);
  $.ajax({
    type: "GET",
    url: "{{ url('admin/products') }}/"+id,
    dataType: "json",
    success: function (response) {
      $('#excellent_offer').val('');
      $('#good_offer').val('');
      $('#fair_offer').val('');
      $('#poor_offer').val('');
      $('#modal-product').modal();
    }
  });
}

function percent(amount, offer) {
  console.log(amount);
  goodPrice = parseFloat($('#good').val());
  fairPrice = parseFloat($('#fair').val());
  poorPrice = parseFloat($('#poor').val());
  amount = parseFloat(amount);
  if(offer == 'good'){
    return amount - (goodPrice / 100 * amount);
  }
  if(offer == 'fair'){
    return amount - (fairPrice / 100 * amount);
  }
  if(offer == 'poor'){
    return amount - (poorPrice / 100 * amount);
  }
}
</script>
@endsection