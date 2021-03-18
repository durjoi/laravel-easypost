@extends('layouts.front')
@section('content')
    <div class="pt-70 pb50">
        <div class="container pt-50">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
	                                @include('common.component.preloader.index')
                                    <div id="empty-cart" class="hideme" align="center"></div>
                                    <div id="my-cart-details" class="hideme"></div>
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
        GenerateCartDetails();
        function GenerateCartDetails() 
        {
            var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
            $.ajax({
                type: "POST",
                url: "{{ url('api/web/cart') }}",
                data : { 'sessionCart' : sessionCart },
                dataType: "json",
                success: function (response) {
                    $('#preloader, .addOnPreloader').addClass('hideme');
                    if (response.hasCart == false) {
                        $('#empty-cart').html(response.cartHtml);
                        $('#empty-cart').removeClass('hideme');
                        $('#cart-total-summary, #cart-checkout, #my-cart-details').addClass('hideme');
                        $('#my-cart-details').html('');
                        localStorage.clear();
                    } else {
                        $('#my-cart-details').html(response.cartHtml);
                        $('#my-cart-details, #cart-total-summary, #cart-checkout').removeClass('hideme');
                        $('.cart-subtotal, .cart-total').html(response.subTotal);
                        
                        $('.cart-item-quantity').on('change', function () {
                            $('#preloader, .addOnPreloader').removeClass('hideme');
                            $('#cart-total-summary, #cart-checkout').addClass('hideme');
                            $('#my-cart-details').html('');
                            var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
                            var cart_key = $(this).attr('data-attr-id');
                            sessionCart[cart_key]['quantity'] = $(this).val();
                            localStorage.setItem("sessionCart", encryptData(JSON.stringify(sessionCart)));
                            GenerateCartDetails();
                        });

                        $('.removeItem').on('click', function () {
                            var newSessionCart = [];
                            var cartId = $(this).attr('data-attr-id');
                            var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
                            $.each( sessionCart, function( key, value ) {
                                if (key != cartId) {
                                    newSessionCart.push(value);
                                }
                            });
                            localStorage.setItem("sessionCart", encryptData(JSON.stringify(newSessionCart)));
                            GenerateCartDetails();
                            
                        });
                    }
                }
            });
        }
    });
    
</script>
@endsection




                                        
                                            
                                            