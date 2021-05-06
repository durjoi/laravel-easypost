var baseUrl = $('body').attr('data-url');

/**
 * Telephone Masking
 */

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
        } else {
            swalWarning ("Oops", "Mobile Number is invalid", "warning", "Close");
        }
    }
});

// on keyup / change flag: reset
telInput.on("keyup change", reset);



/**
 * Re-Captcha
 */

// function onSubmit(token) {
//     document.getElementById("registration-form").submit();
// }

// grecaptcha.ready(function() {

//     // grecaptcha.execute('6Le2wMcaAAAAAIo15PAwm4B3zvNn4pCdXxyffSdV', {action: 'submit'}).then(function(token) {
//     grecaptcha.execute('6LdL88gaAAAAADz37QBeHlxzoqGdDKaVaBkVOiBO', {action: 'submit'}).then(function(token) {
//         console.log(token);
//         return false;
//         // if ($('input[name=fname]').val() == '') {
//         //     swalWarning ("Oops!", "First Name is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=lname]').val() == '') {
//         //     swalWarning ("Oops!", "Last Name is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=phone]').val() == '') {
//         //     swalWarning ("Oops!", "Mobile Number is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=address1]').val() == '') {
//         //     swalWarning ("Oops!", "Address is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=city]').val() == '') {
//         //     swalWarning ("Oops!", "City is required", "warning", "Close");
//         //     return false;
//         // } else if ($('select[name=state_id]').val() == '') {
//         //     swalWarning ("Oops!", "State is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=zip_code]').val() == '') {
//         //     swalWarning ("Oops!", "Zip Code is required", "warning", "Close");
//         //     return false;
//         // } else if ($('input[name=email]').val() == '') {
//         //     swalWarning ("Oops!", "Email Address is required", "warning", "Close");
//         //     return false;
//         // }
//     });
// });
// function onClick(e) {
//     e.preventDefault();
//     grecaptcha.ready(function() {

//         // grecaptcha.execute('6Le2wMcaAAAAAIo15PAwm4B3zvNn4pCdXxyffSdV', {action: 'submit'}).then(function(token) {
//         grecaptcha.execute('6LdL88gaAAAAADz37QBeHlxzoqGdDKaVaBkVOiBO', {action: 'submit'}).then(function(token) {
//             console.log(token);
//             return false;
//             // if ($('input[name=fname]').val() == '') {
//             //     swalWarning ("Oops!", "First Name is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=lname]').val() == '') {
//             //     swalWarning ("Oops!", "Last Name is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=phone]').val() == '') {
//             //     swalWarning ("Oops!", "Mobile Number is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=address1]').val() == '') {
//             //     swalWarning ("Oops!", "Address is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=city]').val() == '') {
//             //     swalWarning ("Oops!", "City is required", "warning", "Close");
//             //     return false;
//             // } else if ($('select[name=state_id]').val() == '') {
//             //     swalWarning ("Oops!", "State is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=zip_code]').val() == '') {
//             //     swalWarning ("Oops!", "Zip Code is required", "warning", "Close");
//             //     return false;
//             // } else if ($('input[name=email]').val() == '') {
//             //     swalWarning ("Oops!", "Email Address is required", "warning", "Close");
//             //     return false;
//             // }
//         });
//     });
// }

grecaptcha.ready(function() {
    grecaptcha.execute('6LdL88gaAAAAADz37QBeHlxzoqGdDKaVaBkVOiBO', {action: 'registration'})
    .then(function(token) {
        //console.log(token);
        document.getElementById('recaptcha').value=token;
    });
});
function getRecaptcha (recaptchaSecretKey) 
{

    $.ajax({
        type: "POST",
        url: 'https://www.google.com/recaptcha/api/siteverify?secret=6LdL88gaAAAAAPEuyF5k8qLRzu4XH9pA7g9nQI2D&response='+recaptchaSecretKey+'',
        dataType: "json",
        success: function (response) {
            console.log(response);
            // if (response.status == 1001) {
            //     swalWarning ("Oops!", response.error, "warning", "Close");
            //     return false;
            // } else {
            //     var countryCode = $('.selected-dial-code').html();
            //     var phone = $('input[name=phone]').val();
            //     var generatePhone = countryCode + '' + phone;
            //     $('input[name=phone]').val(generatePhone);
            //     alert(generatePhone);
            //     onClick();
            // }
            // return false;
        }
    });
    
}

/**
 * Registration
 */


 $(function () {
     
    $('#registration-form').on('submit', function () {
        getRecaptcha($('#recaptcha').val());
        alert($('#recaptcha').val());

        // if ($('input[name=fname]').val() == '') {
        //     swalWarning ("Oops!", "First Name is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=lname]').val() == '') {
        //     swalWarning ("Oops!", "Last Name is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=phone]').val() == '') {
        //     swalWarning ("Oops!", "Mobile Number is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=address1]').val() == '') {
        //     swalWarning ("Oops!", "Address is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=city]').val() == '') {
        //     swalWarning ("Oops!", "City is required", "warning", "Close");
        //     return false;
        // } else if ($('select[name=state_id]').val() == '') {
        //     swalWarning ("Oops!", "State is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=zip_code]').val() == '') {
        //     swalWarning ("Oops!", "Zip Code is required", "warning", "Close");
        //     return false;
        // } else if ($('input[name=email]').val() == '') {
        //     swalWarning ("Oops!", "Email Address is required", "warning", "Close");
        //     return false;
        // }
        // $.ajax({
        //     type: "PATCH",
        //     url: baseUrl+'/customer/auth/register',
        //     data: {
        //         'email' : $('input[name=email]').val()
        //     },
        //     dataType: "json",
        //     success: function (response) {
        //         if (response.status == 1001) {
        //             swalWarning ("Oops!", response.error, "warning", "Close");
        //             return false;
        //         } else {
        //             var countryCode = $('.selected-dial-code').html();
        //             var phone = $('input[name=phone]').val();
        //             var generatePhone = countryCode + '' + phone;
        //             $('input[name=phone]').val(generatePhone);
        //             alert(generatePhone);
        //             onClick();
        //         }
        //         // return false;
        //     }
        // });
        return false;
    });



    $('#btn-checkout-loader, #checkoutCompletedSection').addClass('hideme');
    $(document).on('submit', '#form-checkout', function () {
        var countryCode = $('.selected-dial-code').html();
        // return false;
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
            url: baseUrl+'/device',
            data: obj,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    $('#checkoutCompleted').html(response.message);
                    $('#checkoutInProgress').html('');
                    $('#checkoutCompletedSection').removeClass('hideme');
                    localStorage.clear();
                } else if (response.status == 301) {
                    swalWarning ("Congratulations!", response.message, "warning", "Close");
                    window.location.href = '../'+response.redirectTo;
                    localStorage.clear();
                } else {
                    swalWarning ("Oops!", response.message, "warning", "Close");
                    $('#btn-checkout').removeClass('hideme');
                }
                $('#btn-checkout-loader').addClass('hideme');
            }
        });
        return false;
    });

    $('#signUp').on('click', function () {
        window.location.href = "register";
    });

});  



function has(object, key) {
    return object ? hasOwnProperty.call(object, key) : false;
}