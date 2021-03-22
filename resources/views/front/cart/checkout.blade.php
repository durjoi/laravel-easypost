@extends('layouts.front')
@section('content')

    <div class="pt-70 pb50">
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
                                                    <div id="divCartDetails"></div>
                                                    
                                                    <div class="form-row" id="payment-row"></div>
                                                    <div class="form-group">
                                                        <div class="float-right">
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
@endsection
@section('page-js')
<script>
    $(function () {
        $('#btn-checkout-loader, #checkoutCompletedSection').addClass('hideme');
        $(document).on('submit', '#form-checkout', function () {
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
                    obj[field.name] = propVal;
                }
            });
            obj['cart'] = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
            $.ajax({
                type: "POST",
                url: "{{ url('device') }}",
                data: obj,
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                        $('#checkoutCompleted').html(response.message);
                        $('#checkoutInProgress').html('');
                        $('#checkoutCompletedSection').removeClass('hideme');
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
    // $(function () {
    //     GenerateCartDetails();
    //     function GenerateCartDetails() 
    //     {
    //         var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
    //         $.ajax({
    //             type: "POST",
    //             url: "{{ url('api/web/cart') }}",
    //             data : { 'sessionCart' : sessionCart },
    //             dataType: "json",
    //             success: function (response) {
    //                 $('#preloader, .addOnPreloader').addClass('hideme');
    //                 if (response.hasCart == false) {
    //                     $('#empty-cart').html(response.cartHtml);
    //                     $('#empty-cart').removeClass('hideme');
    //                     $('#cart-total-summary, #cart-checkout').addClass('hideme');
    //                 } else {
    //                     $('#my-cart-details').html(response.cartHtml);
    //                     $('#my-cart-details, #cart-total-summary, #cart-checkout').removeClass('hideme');
    //                     $('.cart-subtotal, .cart-total').html(response.subTotal);
                        
    //                     $('.cart-item-quantity').on('change, click', function () {
    //                         $('#preloader, .addOnPreloader').removeClass('hideme');
    //                         $('#my-cart-details').html('');
    //                         var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
    //                         var cart_key = $(this).attr('data-attr-id');
    //                         sessionCart[cart_key]['quantity'] = $(this).val();
    //                         localStorage.setItem("sessionCart", encryptData(JSON.stringify(sessionCart)));
    //                         GenerateCartDetails();
    //                     });

    //                     $('.removeItem').on('click', function () {
    //                         var newSessionCart = [];
    //                         var cartId = $(this).attr('data-attr-id');
    //                         var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
    //                         $.each( sessionCart, function( key, value ) {
    //                             if (key != cartId) {
    //                                 newSessionCart.push(value);
    //                             }
    //                         });
    //                         localStorage.setItem("sessionCart", encryptData(JSON.stringify(newSessionCart)));
    //                         GenerateCartDetails();
                            
    //                     });
    //                 }
    //             }
    //         });
    //     }
    // });
    
</script>
@endsection



