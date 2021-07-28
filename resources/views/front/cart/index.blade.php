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

                <div class="row pt15 hideme" id="cart-checkout">
                    <div class="offset-md-6 col-md-6">
                        <div class="form-group">

                            <a href="{{ url('/cart/checkout') }}" id="checkout" class="btn btn-warning btn-md btn-block">Proceed to Checkout</a>
                            @if(isset($isValidAuthentication))
                            @if(isset($isValidAuthentication) && $isValidAuthentication == false)
                            <!-- <a href="{{ url('/cart/checkout') }}" id="checkout" class="btn btn-warning btn-md btn-block">Proceed to Checkout</a> -->
                            @elseif(isset($isValidAuthentication) && $isValidAuthentication == true)
                            <!-- <form id="form-auth-checkout">
                                <button type="submit" class="btn btn-warning btn-md btn-block" id="form-button-checkout">
                                    Proceed to Checkout
                                </button>
                            </form> -->
                            @endif
                            @endif

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
    $(function() {
        var baseUrl = $('body').attr('data-url');
        if (localStorage.getItem("sessionCart")) {
            GenerateCartDetails();
        } else {
            $('#empty-cart').removeClass('hideme');
            $('#empty-cart').html('<div class="form-group"><img src="/assets/images/empty-cart.png" class="img-fluid"></div>');
            $('#preloader, .addOnPreloader').addClass('hideme');

        }
        $('#form-auth-checkout').on('submit', function() {
            $('#form-button-checkout').addClass('disabled');
            $('#form-button-checkout').html('<i class="fas fa-spinner fa-spin"></i> Please wait...');
            var obj = {
                '_token': '',
                'fname': '',
                'lname': '',
                'address1': '',
                'address2': '',
                'city': '',
                'state_id': '',
                'zip_code': '',
                'email': '',
                'phone': '',
                'payment_method': '',
                'account_username': '',
                'bank': '',
                'account_name': '',
                'account_number': '',
                'cart': null
            };
            jQuery.each($(this).serializeArray(), function(i, field) {
                if (has(obj, field.name)) {
                    var propVal = field.value;
                    obj[field.name] = propVal;
                }
            });
            obj['cart'] = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
            $.ajax({
                type: "POST",
                url: "{{ url('device/authStore') }}",
                data: obj,
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        $('#checkoutCompleted').html(response.message);
                        $('#checkoutInProgress').html('');
                        $('#checkoutCompletedSection').removeClass('hideme');
                        $('#form-button-checkout').removeClass('disabled');
                        localStorage.clear();
                    } else if (response.status == 301) {
                        swalWarning("Congratulations!", response.message, "success", "Done");
                        setTimeout(function() {
                            window.location.href = baseUrl + '/' + response.redirectTo;
                        }, 3000);

                        localStorage.clear();
                    } else {
                        swalWarning("Oops!", response.message, "warning", "Close");
                        $('#form-button-checkout').removeClass('disabled');
                        $('#btn-checkout').removeClass('hideme');
                    }

                    $('#form-button-checkout').html('Proceed to checkout');
                    $('#btn-checkout-loader').addClass('hideme');
                }
            });
            return false;
            return false;
        })
    });
</script>
@endsection