@extends('layouts.front')
@section('content')

    <div class="pt-70 pb50 d-none" id="whole-content">
        <div class="container pt-50">
            <div class="row">
                <div class="col-lg-8">
                    <div id="sectionPreloader" class="hideme">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                            
                                        @include('common.component.preloader.index')
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row hideme" id="checkoutCompletedSection">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div id="checkoutCompleted" > </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="checkoutInProgress">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <div id="my-cart-details" class="hideme"></div> -->
                                        <div class="media-body" id="checkout-div">
                                            <div>
                                                <div id="chk-offer"></div>
                                                <form action="{{ url('device') }}" id="form-checkout" method="POST">
                                                    @csrf
                                                    <h5>Provide your shipping address</h5>
                                                    <p>We use this information to create your shipping labels so you can send your item to us for free!</p>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">First Name</label>
                                                            <input type="text" name="fname" class="form-control form-control-sm"> <!-- Juan -->
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">Last Name</label>
                                                            <input type="text" name="lname" class="form-control form-control-sm"> <!-- Dela Cruz -->
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">Address Line 1</label>
                                                            <input type="text" name="address1" class="form-control form-control-sm"> <!-- 179 N Harbor Dr -->
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">Address Line 2 (Optional)</label>
                                                            <input type="text" name="address2" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label class="col-form-label col-form-label-sm">City</label>
                                                            <input type="text" name="city" class="form-control form-control-sm"> <!-- Redondo Beach -->
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="col-form-label col-form-label-sm">State</label>
                                                            {!! Form::select('state_id', $stateList, '', ['class'=>'custom-select select-sm']) !!}  <!-- CA -->
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="col-form-label col-form-label-sm">Zip Code</label>
                                                            <input type="text" name="zip_code" class="form-control form-control-sm"> <!-- 90277 -->
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">Email Address</label>
                                                            <input type="email" name="email" class="form-control form-control-sm">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="col-form-label col-form-label-sm">
                                                                        Phone 
                                                                    </label>
                                                                    <span id="valid-msg" class="hideme text-green">Valid</span>
                                                                    <span id="error-msg" class="hideme text-red">Invalid number</span>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input id="phone" type="tel" name="phone" style="width: 100% !important;" class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                            <!-- <input type="text" name="phone" class="form-control form-control-sm"> -->
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                        
                                                            

                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label class="col-form-label col-form-label-sm">How would you like to be paid?</label>
                                                            {!! Form::select('payment_method', $paymentList, '', ['class'=>'custom-select select-sm','id'=>'payment_method']) !!}
                                                        </div>
                                                    </div>
                                                    <div id="divCartDetails"></div>
                                                    
                                                    <div class="form-row" id="payment-row"></div>
                                                    <div class="form-group d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <input type="checkbox" value="terms-and-condition" id="terms-and-condition"/>
                                                            <label for="checkbox">Agree to <a href="#" data-toggle="modal" data-target="#terms-and-conditions-modal">terms and conditions.</a></label>
                                                        </div>
                                                        <div>
                                                            <button type="submit" class="btn btn-warning btn-md" id="btn-checkout">Checkout</button>
                                                            <button type="button" class="btn btn-warning btn-md disabled hideme" id="btn-checkout-loader"><i class="fas fa-spinner fa-spin"></i> Please wait...</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-50 hideme" id="cart-total-summary">
                            <div class="offset-md-6 col-md-6">
                                <div class="card">
                                    <div class="card-body" style="margin: 0 -6px -21px -6px;">
                                        <div class="mb10 mt-10">
                                            <h5>Cart Totals</h5>
                                        </div>
                                        
                                        <div class="row box-gray pt10">
                                            <div class="col-md-5">
                                                <b>Subtotal</b>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group mb10 cart-subtotal"></div>
                                            </div>
                                        </div>
                                        <div class="row box-gray pt10">
                                            <div class="col-md-5">
                                                <b>Shipping</b>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    Free shipping
                                                </div>
                                                <div class="form-group mb10">
                                                    Shipping options will be updated during checkout.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row box-gray pt10">
                                            <div class="col-md-5">
                                                <b>Total</b>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group mb10 cart-total">
                                                    $2,980.00
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row pt-50 hideme" id="cart-checkout">
                            <div class="offset-md-6 col-md-6">
                                <div class="form-group">
                                    <a href="{{ url('/cart/checkout') }}" id="checkout" class="btn btn-warning btn-md btn-block">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                
                    @include('front.cart.sidebar')
                </div>
            </div>
        </div>
    </div>

    @include('admin.modals.terms-and-conditions.index')
@endsection

@section('page-js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    <script>
        if(document.readyState == "loading"){
            if(!localStorage.getItem('sessionCart')){
                localStorage.setItem("cart-empty","Please add some item in your cart first");
                window.location.href = '../../';
            } else {
                var cart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
                if(cart.length < 0){
                    window.location.href = '../../';
                }
            }   
        }

        window.onload =  function(){
            $("#whole-content").removeClass("d-none");
        }

        var telInput = $("#phone"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");

        $("#agree-terms-button").click(function(){
            const terms_and_condition = document.getElementById('terms-and-condition');
            terms_and_condition.checked = true;
        });

        // initialise plugin
        telInput.intlTelInput({

            allowExtensions: true,
            formatOnDisplay: true,
            autoFormat: true,
            autoHideDialCode: true,
            autoPlaceholder: true,
            defaultCountry: "auto",
            ipinfoToken: "yolo",

            nationalMode: false,
            numberType: "MOBILE",
            //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            preferredCountries: ['sa', 'ae', 'qa','om','bh','kw','ma'],
            preventInvalidNumbers: true,
            separateDialCode: true,
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"
        });

        var reset = function() {
            telInput.removeClass("error");
            errorMsg.addClass("hideme");
            validMsg.addClass("hideme");
        };

        // on blur: validate
        telInput.blur(function() {
            reset();
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    // validMsg.removeClass("hideme");
                } else {
                    swalWarning ("Oops", "Mobile Number is invalid", "warning", "Close");
                    // $('#phone').focus();
                    // telInput.addClass("error");
                    // errorMsg.removeClass("hideme");
                }
            }
        });

        // on keyup / change flag: reset
        telInput.on("keyup change", reset);

        $(function () {
            $('#btn-checkout-loader, #checkoutCompletedSection').addClass('hideme');
            $(document).on('submit', '#form-checkout', function (e) {
                var countryCode = $('.selected-dial-code').html();
                const terms_and_condition = document.getElementById('terms-and-condition');
                // return false;

                if(!terms_and_condition.checked){
                    swal({
                        title: "Did not agree to terms and conditions",
                        text: "Please agree to terms and conditions",
                        icon: "info",
                        buttons: "Close",
                    });
                    e.preventDefault();
                    return;
                }
                $('#btn-checkout-loader').removeClass('hideme');
                $('#btn-checkout').addClass('hideme');
                var obj = {
                    '_token' : '',
                    'fname' : '',
                    'lname' : '',
                    'address1' : '',
                    'address2' : '',
                    'city' : '',
                    'state_id' : '',
                    'zip_code' : '',
                    'email' : '',
                    'phone' : '',
                    'payment_method' : '',
                    'account_username' : '',
                    'bank' : '',
                    'account_name' : '',
                    'account_number' : '',
                    'cart' : null
                };
                jQuery.each( $(this).serializeArray(), function( i, field ) {
                    if (has(obj, field.name)) {
                        var propVal = field.value;
                        if (field.name == 'phone') {
                            obj[field.name] = countryCode + '' + propVal;
                        } else {
                            obj[field.name] = propVal;
                        }
                    }
                });
                
                obj['cart'] = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
                $.ajax({
                    type: "POST",
                    url: "{{ url('device') }}",
                    data: obj,
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        if (response.status == 200) {
                            $('#checkoutCompleted').html(response.message);
                            $('#checkoutInProgress').html('');
                            $('#checkoutCompletedSection').removeClass('hideme');
                            localStorage.clear();
                        } else if (response.status == 301) {
                            swal({
                                title : "Congratulations!",
                                text : response.message,
                                icon : "success", 
                                buttons: "Close",
                            })
                            window.location.href = '../'+response.redirectTo;
                            localStorage.clear();
                        } else {
                            swal({
                                title : "Oops!",
                                text : response.message,
                                icon : "warning", 
                                buttons: "Close",
                            })
                            $('#btn-checkout').removeClass('hideme');
                        }
                        $('#btn-checkout-loader').addClass('hideme');
                    }
                });
                return false;
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

        function has(object, key) {
            return object ? hasOwnProperty.call(object, key) : false;
        }
        
    </script>
@endsection

@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
    <style>
        .intl-tel-input {
            width: 100%;
        }

        .list-title {
            color: orange;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endsection
