@extends('layouts.front')
@section('content')
<div class="pt-70" id="device-content" data-brand="{{ $brand }}">
  @if($chkproduct)
  <section>
    <div class="container pt-70 pb-50">
      <h2 id="header">{{ $brand }}</h2>
      <div class="row" id="row-network" style="padding-top: 15px;">
        <div class="col-md-12">
          <label>Choose Carrier</label>
          <div class="form-row">
            @foreach($networks as $network)
            <div class="form-group col-md-2">
              <div class="card">
                <div class="card-body text-center">
                  <a href="javascript:void(0)" onclick="network('<?php echo $network->network; ?>')">
                    @if($network->network == 'AT&T')
                    <img src="{{ url('assets/images/network/1.png') }}" class="img-fluid">
                    @endif
                    @if($network->network == 'Sprint')
                    <img src="{{ url('assets/images/network/2.png') }}" class="img-fluid">
                    @endif
                    @if($network->network == 'T-Mobile')
                    <img src="{{ url('assets/images/network/3.png') }}" class="img-fluid">
                    @endif
                    @if($network->network == 'Verizon')
                    <img src="{{ url('assets/images/network/4.png') }}" class="img-fluid">
                    @endif
                    @if($network->network == 'Unlocked')
                    <img src="{{ url('assets/images/network/5.png') }}" class="img-fluid">
                    @endif
                    @if($network->network == 'Others')
                    <img src="{{ url('assets/images/network/6.png') }}" class="img-fluid">
                    @endif
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="row" id="row-devices" style="padding-top: 15px; display: none"></div>
      <div class="row" id="row-checkout" style="padding-top: 15px; display: none">
        <div class="col-md-10 offset-md-1">
          <div class="card">
            <div class="card-body">
              <div class="media">
                <img src="" class="mr-3" id="device-image" style="width: 250px;">
                <div class="media-body" id="condition-div">
                  <div>
                    <h3>What condition is your device in?</h3>
                    <hr>
                    <div class="btn-group-toggle" data-toggle="buttons">
                      <label class="btn btn-outline-warning radio-btn active">
                        <input type="radio" class="radio-device" name="device_type" value="1" autocomplete="off" checked> Excellent
                      </label>
                      <label class="btn btn-outline-warning radio-btn">
                        <input type="radio" class="radio-device" name="device_type" value="2" autocomplete="off"> Good
                      </label>
                      <label class="btn btn-outline-warning radio-btn">
                        <input type="radio" class="radio-device" name="device_type" value="3" autocomplete="off"> Fair
                      </label>
                      <label class="btn btn-outline-warning radio-btn">
                        <input type="radio" class="radio-device" name="device_type" value="4" autocomplete="off"> Broken
                      </label>
                    </div>
                    <br>
                    <h3>Size</h3>
                    <hr>
                    <div class="btn-group-toggle" data-toggle="buttons" id="device-storage"></div>
                  </div>
                  <div class="row" style="padding-top: 20px;">
                    <div class="col-md-8">
                      <div class="card" id="condition"></div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <h5>Your Cash Offer</h5>
                        <div style="font-size: 40px; padding-bottom: 15px" id="device-amount"></div>
                        <a href="javascript:void(0)" id="checkout" class="btn btn-warning btn-md btn-block">Get Paid</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="media-body" id="checkout-div" style="display:none">
                  <div>
                    <div id="chk-offer"></div>
                    <form action="{{ url('device') }}" method="POST">
                    @csrf
                      <h5>Provide your shipping address</h5>
                      <p>We use this information to create your shipping labels so you can send your item to us for free!</p>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">First Name</label>
                          <input type="text" name="fname" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">Last Name</label>
                          <input type="text" name="lname" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">Address Line 1</label>
                          <input type="text" name="address1" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">Address Line 2 (Optional)</label>
                          <input type="text" name="address2" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label class="col-form-label col-form-label-sm">City</label>
                          <input type="text" name="city" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-4">
                          <label class="col-form-label col-form-label-sm">State</label>
                          {!! Form::select('state_id', $stateList, '', ['class'=>'custom-select select-sm']) !!}
                        </div>
                        <div class="form-group col-md-4">
                          <label class="col-form-label col-form-label-sm">Zip Code</label>
                          <input type="text" name="zip_code" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">Email Address</label>
                          <input type="email" name="email" class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">Phone</label>
                          <input type="text" name="phone" class="form-control form-control-sm">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label class="col-form-label col-form-label-sm">How would you like to be paid?</label>
                          {!! Form::select('payment_method', $paymentList, '', ['class'=>'custom-select select-sm','id'=>'payment_method']) !!}
                        </div>
                      </div>
                      <div class="form-row" id="payment-row"></div>
                      <div class="form-group">
                        <div class="float-right">
                          <input type="hidden" name="amount" id="input-amount">
                          <input type="hidden" name="storage" id="input-storage">
                          <input type="hidden" name="network" id="input-network">
                          <input type="hidden" name="brand" id="input-brand">
                          <input type="hidden" name="device_type" id="input-device_type">
                          <input type="hidden" name="model" id="input-model">
                          <button type="submit" class="btn btn-warning btn-md">Checkout</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @else
  <section>
    <div class="container pt-70 pb-50">
      <h2 id="header">{{ $brand }}</h2>
      <div class="row" id="row-network" style="padding-top: 15px;">
        <div class="col-md-12">
          <div class="alert alert-danger" role="alert">
            No available devices for {{ $brand }}. Please try other brand.
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif
</div>
@endsection

@section('page-css')
<link href="{{ url('assets/css/products.css') }}" rel="stylesheet">
<style>
.radio-btn {
  width: 110px;
  color: #000;
}
.select-sm {
  height: 31px;
  padding: 0px 8px;
  font-size: 14px;
}
</style>
@endsection

@section('page-js')
{!! JsValidator::formRequest('App\Http\Requests\SellRequest') !!}
<script>
$(document).ready(function () {
  $('.radio-device').change(function(){
    var brand = $('#device-content').attr('data-brand');
    var network = $('#device-content').attr('data-network');
    var id = $('#device-content').attr('data-id');
    $.ajax({
      type: "POST",
      url: "{{ url('products/model') }}",
      data: {
        id: id,
        brand_id: brand,
        network: network,
        device_type: $('input[name=device_type]:checked').val(),
        storage: $('input[name=storage]:checked').val()
      },
      dataType: "json",
      success: function (response) {
        $('#header').html(brand+' / '+network+' / '+response.product.model);
        $('#row-devices').slideUp();
        $('#row-checkout').fadeIn();
        $('#device-image').attr('src', response.photo);
        $('#condition').html(response.condition);
        $('#device-amount').html('<b>$'+response.amount+'</b>');
        $('#input-amount').val(response.amount);
        $('#input-storage').val($('input[name=storage]:checked').val());
        $('#input-network').val(network);
        $('#input-brand').val(brand);
        $('#input-device_type').val($('input[name=device_type]:checked').val());
        $('#input-model').val(response.product.model);
      }
    });
  });

  $('#checkout').click(function(){
    var amount = $('#input-amount').val();
    var storage = $('#input-storage').val();
    $('#condition-div').hide();
    $('#checkout-div').fadeIn();
    $('#chk-offer').html(
      '<h4>Cash Offer: $'+amount+' &mdash; Size: '+storage+'</h4>'+
      '<hr>'
    );
  });

  $('#payment_method').change(function(){
    var payment = $(this).val();
    $('#payment-row').html(
      '<div class="spinner-border" role="status">'+
        '<span class="sr-only">Loading...</span>'+
      '</div>'
    );
    $.ajax({
      type: "POST",
      url: "{{ url('products/sell/payment-method') }}",
      data: {
        payment: payment
      },
      dataType: "json",
      success: function (response) {
        $('#payment-row').html(response.content);
      }
    });
  });
});

function network(name){
  $('#row-network').slideUp();
  $('#row-devices').fadeIn();
  var brand = $('#device-content').attr('data-brand');
  $('#device-content').attr('data-network', name);
  $('#header').html(brand+' / '+name);
  $.ajax({
    type: "POST",
    url: "{{ url('products/network') }}",
    data: {
      brand: brand,
      network: name
    },
    dataType: "json",
    success: function (response) {
      $('#row-devices').html(response.content);
    }
  });
}

function getoffer(id){
  var brand = $('#device-content').attr('data-brand');
  var network = $('#device-content').attr('data-network');
  $('#device-content').attr('data-id', id);
  $.ajax({
    type: "POST",
    url: "{{ url('products/model') }}",
    data: {
      id: id,
      brand_id: brand,
      network: network,
      device_type: $('input[name=device_type]:checked').val(),
      storage: $('input[name=storage]:checked').val()
    },
    dataType: "json",
    success: function (response) {
      $('#header').html(brand+' / '+network+' / '+response.product.model);
      $('#row-devices').slideUp();
      $('#row-checkout').fadeIn();
      $('#device-storage').html(response.storagelist);
      $('#device-image').attr('src', response.photo);
      $('#condition').html(response.condition);
      $('#device-amount').html('<b>$'+response.amount+'</b>');
      $('#input-amount').val(response.amount);
      $('#input-storage').val($('input[name=storage]:checked').val());
      $('#input-network').val(network);
      $('#input-brand').val(brand);
      $('#input-device_type').val($('input[name=device_type]:checked').val());
      $('#input-model').val(response.product.model);
    }
  });
}

function storage(id){
  var brand = $('#device-content').attr('data-brand');
  var network = $('#device-content').attr('data-network');
  var id = $('#device-content').attr('data-id');
  $.ajax({
    type: "POST",
    url: "{{ url('products/model') }}",
    data: {
      id: id,
      brand_id: brand,
      network: network,
      device_type: $('input[name=device_type]:checked').val(),
      storage: $('input[name=storage]:checked').val()
    },
    dataType: "json",
    success: function (response) {
      $('#header').html(brand+' / '+network+' / '+response.product.model);
      $('#row-devices').slideUp();
      $('#row-checkout').fadeIn();
      $('#device-image').attr('src', response.photo);
      $('#condition').html(response.condition);
      $('#device-amount').html('<b>$'+response.amount+'</b>');
      $('#input-amount').val(response.amount);
      $('#input-storage').val($('input[name=storage]:checked').val());
      $('#input-network').val(network);
      $('#input-brand').val(brand);
      $('#input-device_type').val($('input[name=device_type]:checked').val());
      $('#input-model').val(response.product.model);
    }
  });
}
</script>
@endsection