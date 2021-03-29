@extends('layouts.front')
@section('content')
    <div class="pt-70 pb50">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <h3>About Us</h3>
                                            <hr />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 form-group">
                                            <p>
                                                <span class="fontColorThemeGreen">
                                                    At <b>TronicsPay</b>, our mission is to transform the way 
                                                    consumers recycle their used cell phones. 
                                                </span>
                                                Whether it’s selling 
                                                an old phone or raising money through charitable fundraising, 
                                                we guarantee satisfaction by providing a fast, easy, and secure 
                                                portal to sell your mobile phones. We offer fast payment options, 
                                                provide free shipping, and ensure 
                                                <span class="fontColorThemeGreen"><u>competitive prices.</u></span>
                                            </p>
                                            <p>
                                                That’s not all.  We help businesses thrive by purchasing retired 
                                                fleets of phones, raise funds for charitable causes, and 
                                                facilitate recycling to reduce
                                            </p>
                                            <p>
                                                e-waste. We truly believe the responsible alternative to 
                                                tossing your mobile phones is one that serves the environment, 
                                                your community, and your wallet.
                                            </p>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <img src="{{ url('library/images/aboutus.jpg') }}" class="img-fluid">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group aboutus-badge">
                                            <div class="row">

                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/datasecurity.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">Data Security</span> <br />
                                                                        We remove personal data from all smartphones.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/trust.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">Trust</span> <br />
                                                                        No auction fees, difficult listings, or dangerous meetings with strangers.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/freeshipping.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">Free Shipping</span> <br />
                                                                        Print our prepaid shipping label or receive a free shipping kit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/fast-payment.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">Fast Payment</span> <br />
                                                                        Payment will be processed within 24hrs of receiving your device.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/no-risk.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">No Risk</span> <br />
                                                                        Request your phone back at no cost during the processing period.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="{{ url('/library/images/best-values.png') }}">
                                                                </div>
                                                                <div class="col-10">
                                                                    <p class=" ml10">
                                                                        <span class="font-bold">Best Value</span> <br />
                                                                        Sell with confidence. You are guaranteed highest offers.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-4">
                    @include('partial.front.sidebarFCR')
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




                                        
                                            
                                            