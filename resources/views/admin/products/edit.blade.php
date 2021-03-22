@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Add New Device</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item">Manage Products</li>
            <li class="breadcrumb-item active">Create</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <form action="{{ url('admin/products', $product->id) }}" method="POST">
      @csrf
      @method('PUT')
      @include('admin.products.form')
      <input type="hidden" id="good" value="{{ $config->good }}">
      <input type="hidden" id="fair" value="{{ $config->fair }}">
      <input type="hidden" id="poor" value="{{ $config->poor }}">
      <input type="hidden" name="id" id="product_id" value="{{ $product->id }}" />
    </form>
  </section>
</div>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ url('assets/plugins/summernote/summernote-bs4.css') }}">
<style>
.fn label {
  font-weight: normal !important;
}
.fn p {
  font-size: .875rem;
}
.config-legend {
  margin-left: 9px;
}
.pb-50 {
  padding-bottom: 50px;
}
</style>
@endsection

@section('page-js')
{!! JsValidator::formRequest('App\Http\Requests\Admin\ProductRequest') !!}
<script src="{{ url('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
$(document).ready(function() {
  <?php if(session()->has('msg')){ ?>
  toastr.success('<?php echo session('msg'); ?>')
  <?php } ?>
  $('.textarea').summernote({
    tabsize: 2,
    height: 150,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['fontname', ['fontname']],
      ['para', ['ul', 'ol', 'paragraph']],
    ]
  });

  $('.device-check').change(function(){
    if( $(this).is(":checked") ){
      var val = $(this).val();
      if(val == 'Buy'){
        $('#div-offer').show();
        $('#div-amount').hide();
        $('#sku').val('');
      }
      if(val == 'Sell'){
        $('#div-offer').hide();
        $('#div-amount').show();
        $('#sku').val(makesku(7));
      }
      if(val == 'Both'){
        $('#div-offer').show();
        $('#div-amount').show();
        $('#sku').val(makesku(7));
      }
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

  $('#remove-photo').click(function(){
    if(confirm('Are you sure you want to delete this photo?')){
      var id = $('#product_id').val();
      $.ajax({
        type: "POST",
        url: "{{ url('admin/products/deletephoto') }}",
        data: {
          id: id
        },
        dataType: "json",
        success: function (response) {
          
        }
      });
    }
  });
});

function makesku(length) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < length; i++ ) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

function percent(amount, offer) {
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