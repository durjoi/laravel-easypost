function encryptData (string) {
    return CryptoJS.AES.encrypt(string, 'tronicspay secret key');
}

function decryptData (string) {
    var bytes = CryptoJS.AES.decrypt(string.toString(), 'tronicspay secret key');
    return bytes.toString(CryptoJS.enc.Utf8);
}


function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var components = yourNumber.toString().split(".");
    //Comma-fies the first part
    components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return components.join(".");
}

var base_url = $('body').attr('data-url');

function GenerateCartDetails() 
{
    var sessionCart = JSON.parse(decryptData(localStorage.getItem("sessionCart")));
    $.ajax({
        type: "POST",
        url:  base_url+'/api/web/cart',
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