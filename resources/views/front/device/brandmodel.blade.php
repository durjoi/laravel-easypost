@extends('layouts.front')
@section('content')
    <div class="pt-70" id="device-content" data-brand="{{ $brand }}">
        @if($status == 200)
            <section>
                <div class="container pb-50">

                    <div class="row" id="row-checkout" style="padding-top: 15px;">
                        <div class="col-md-10 offset-md-1">
                            <div class="card">
                                <div class="card-body">
                                    <div class="medias" id="condition-div">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{ url($product->photo->photo) }}" class="mr-3 img-fluid" id="device-image">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3>What condition is your device in?</h3>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
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
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <br>
                                                        <h3>Size</h3>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="btn-group-toggle" data-toggle="buttons" id="device-storage">
                                                                {!! $storagelist !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <div class="card" id="condition">{!! $condition !!}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <h5>Your Cash Offer</h5>
                                                        <div style="font-size: 40px; padding-bottom: 15px; font-weight: bold;" id="device-amount"><b>${{ $amount }}</b></div>
                                                        <a href="javascript:void(0)" id="addToCart" class="btn btn-warning btn-md btn-block">Add to Cart</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <h3>Available Carrier</h3>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <div id="section-networks" class="btn-group-toggle" data-toggle="buttons">
                                                                @foreach($product['networks'] as $key => $val)
                                                                    <?php $isActive = ($key == 0) ? 'active' : ''; ?>
                                                                    <label class="btn btn-outline-warning radio-btn btn-carrier {{ $isActive }}" data-attr-id="{{ $val->network_id }}" id="label-carrier-{{ $val->network_id }}" onClick="selectDeviceCarrier({{ $val->network_id }})">
                                                                        <img src="{{ url('/assets/images/network/'.$val->network_id.'.png') }}" class="img-fluid">
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="media">
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
                                                </div>
                                            </form>
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
                var brand = '<?php echo ($status == 200) ? $brand : '' ?>';
                var network = '<?php echo ($status == 200) ? $product->hashedid : '' ?>';
                var id = '<?php echo ($status == 200) ? $product->hashedid : '' ?>';
                $.ajax({
                    type: "POST",
                    url: "{{ url('products/model/filter') }}",
                    data: {
                        id: id,
                        brand_id: brand,
                        network: network,
                        device_type: $('.radio-device:checked').val(), // $(this).val(),
                        storage: $('input[name=storage]:checked').val()
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        $('#condition').html(response.condition);
                        $('#device-amount').html('<b>$'+response.amount+'</b>');

                    }
                });
            });
            
            $('#addToCart').on('click', function () {
                var sessionCart = [];

                if (localStorage.getItem("sessionCart") != null) {
                    var storedItemObj = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
                    var validateExists = false;
                    $.each( storedItemObj, function( key, value ) {
                        if (value.amount == $('#device-amount').html().replace("<b>$", "").replace("</b>", "").replace(",", "") 
                        && value.storage == $('input[name=storage]:checked').val() 
                        && value.carrier_id == $('.btn-carrier.active').attr('data-attr-id')
                        && value.brand == $('#device-content').attr('data-brand') 
                        && value.device_type == $('.radio-device:checked').val() 
                        && value.model == '{{ $model }}') 
                        {
                            validateExists = true;
                            value.quantity = parseFloat(value.quantity) + parseFloat(1);
                        }
                    });
                    if (validateExists != true) {
                        storedItemObj.push({
                            'cart_id' : JSON.parse(decryptData(localStorage.getItem("sessionCart"))).length + 1, 
                            'amount' : $('#device-amount').html().replace("<b>$", "").replace("</b>", "").replace(",", ""), 
                            'storage' : $('input[name=storage]:checked').val(), 
                            'network' : $('#input-network').val(), 
                            'brand' : $('#device-content').attr('data-brand'), 
                            'device_type' : $('.radio-device:checked').val(), 
                            'model' : '{{ $model }}', 
                            'quantity' : 1, 
                            'carrier_id' : $('.btn-carrier.active').attr('data-attr-id')
                        });
                    }
                    localStorage.setItem("sessionCart", encryptData(JSON.stringify(storedItemObj)));
                } else {
                    sessionCart.push({
                        'cart_id' : 1, 
                        'amount' : $('#device-amount').html().replace("<b>$", "").replace("</b>", "").replace(",", ""), 
                        'storage' : $('input[name=storage]:checked').val(), 
                        'network' : $('#input-network').val(), 
                        'brand' : '<?php echo ($status == 200) ? $brand : '' ?>',
                        'device_type' : $('.radio-device:checked').val(), 
                        'model' : '{{ $model }}', 
                        'quantity' : 1, 
                        'carrier_id' : $('.btn-carrier.active').attr('data-attr-id')
                    });
                    localStorage.setItem("sessionCart", encryptData(JSON.stringify(sessionCart)));
                }
                window.location.href = "{{ url('cart') }}";
            })

            $('#checkout').click(function(){
                var amount = $('#input-amount').val();
                var storage = $('#input-storage').val();
                $('#condition-div').hide();
                $('#checkout-div').fadeIn();
                $('#chk-offer').html('<h4>Cash Offer: $'+amount+' &mdash; Size: '+storage+'</h4><hr>');
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

        function selectDeviceCarrier(networkId) 
        {
            $('.btn-carrier').removeClass('active')
            $('#label-carrier-'+networkId).addClass('active');
        }


        function network(name){
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
            $('#row-devices').fadeOut();
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
            var brand = '<?php echo ($status == 200) ? $brand : '' ?>';
            var network = '<?php echo ($status == 200) ? $product->hashedid : '' ?>';
            var id = '<?php echo ($status == 200) ? $product->hashedid : '' ?>';
            $.ajax({
                type: "POST",
                url: "{{ url('products/model/filter') }}",
                data: {
                    id: id,
                    brand_id: brand,
                    network: network,
                    device_type: $('.radio-device:checked').val(), // $('.radio-device:checked').val(),
                    storage: $('input[name=storage]:checked').val()
                },
                dataType: "json",
                success: function (response) {
                    $('#condition').html(response.condition);
                    $('#device-amount').html('<b>$'+response.amount+'</b>');
                }
            });
        }
    </script>
@endsection