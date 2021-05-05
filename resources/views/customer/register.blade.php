@extends('layouts.front')
@section('content')
    <div class="pt-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('customer/auth/register') }}" id="registration-form" method="POST">
                                @csrf
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
                                <!-- <div class="form-row">
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
                                        <label class="col-form-label col-form-label-sm">Email Address</label>
                                        <input type="email" name="email" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label col-form-label-sm">Username</label>
                                        <input type="text" name="username" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label col-form-label-sm">Password</label>
                                        <input type="password" name="password" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label col-form-label-sm">Retype Password</label>
                                        <input type="password" name="password_confirmation" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label col-form-label-sm">Street (Address)</label>
                                        <input type="text" name="street" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label col-form-label-sm">City</label>
                                        <input type="text" name="city" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label col-form-label-sm">State</label>
                                        <input type="text" name="state" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label col-form-label-sm">Zip Code</label>
                                        <input type="text" name="zip" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label col-form-label-sm">Phone</label>
                                        <input type="text" name="phone" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">

                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <input type="hidden" name="cart" value="{{ $cartcount }}">
                                    <button 
                                        class="g-recaptcha btn btn-warning btn-md" 
                                        data-sitekey="{{ $recaptcha['site_key'] }}" 
                                        data-callback="onSubmit" 
                                        data-action="submit"
                                    >
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
@endsection

@section('page-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
    <style>
        .intl-tel-input {
            width: 100%;
        }
    </style>
@endsection
@section('page-js')
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- <script src="https://www.google.com/recaptcha/api.js"></script> -->
    <!-- <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha['site_key'] }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
    <script>
        var telInput = $("#phone"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");

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

        function onSubmit(token) {
            document.getElementById("registration-form").submit();
        }
        // $(function () {

        // });
    </script>
@ensection