var baseUrl = $('body').attr('data-url');

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  }

function makesku(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
  
function percent(amount, offer) {
    goodPrice = parseFloat($('#good').val());
    fairPrice = parseFloat($('#fair').val());
    poorPrice = parseFloat($('#poor').val());
    amount = parseFloat(amount);
    if(offer == 'good'){
      return amount - (goodPrice / 100 * amount);
    }
    if(offer == 'fair'){
      return amount - (fairPrice / 100 * amount);
    }
    if(offer == 'poor'){
      return amount - (poorPrice / 100 * amount);
    }
}

function deleteproduct(hashedId){

    var CSRF_TOKEN = '{{ csrf_token() }}';
    swal({
        title: "Confirmation",
        text: "Do you want to delete selected record?",
        icon: "warning",
          buttons: ["Cancel", "Confirm"],
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: baseUrl+"/admin/products/"+hashedId,
                type: 'DELETE',
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        // toastr.success('Product has been deleted!')
                        productTable.draw();
                        swal({
                            title : "Product has been deleted!",
                            text : data.message,
                            icon : "success", 
                        })
                    }
                }
            });
        } else {
            /*swal({
                title : "Congratulations!",
                text : "Service is still in records",
                icon : "success", 
                buttons: "Done",
            })*/
        }
    });
}



$(function() {
    
    $('#btn-product-buy').click(function () {
        $('#product-buy-form').find("select, input[type=number]").val("")
    });
    $('#btn-product-sell').click(function () {
        $('#product-sell-form').find("select, input[type=number]").val("")
    });
    $('#btn-product-sell').click(function () {
        $('#fieldSellId').val(0);
        $('#isSellForEdit').val('');
        $('#modal-product-sell').modal();
    });


    $('#product-sell-submit').click(function () {
        var data = $('#product-sell-form').serializeArray();
        console.log(data);
        // return false;
        var setId = (data[2].value == 0) ? 0 : data[2].value;
        if ($('#isSellForEdit').val() == "true") {
            var tempId = $('#fieldBuyId').val();
            var newRecord = '<td align="center"><input type="hidden" name="product-storage-id[]" value="'+setId+'">'+data[0].value+' <input type="hidden" name="storage[]" value="'+data[0].value+'"><input type="hidden" name="storage_sell[]" value="'+data[0].value+'"></td>'+
                '<td align="right">$'+data[1].value+' <input type="hidden" name="amount[]" value="'+data[1].value+'"></td>'+
                '<td align="center">'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-primary btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-edit"></i>'+
                    '</a>'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-danger btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-trash"></i>'+
                    '</a>'+
                '</td>';
                $('.tr-id-'+tempId).attr('data-attr-saved', "true");
                // <tr class="tr-id-'+tempId+'" data-attr-saved="true" data-attr-id="'+tempId+'">'+
                
            $('.tr-id-'+tempId).html(newRecord);
        } else {
            
            var newRecord = '<tr class="tr-id" data-attr-saved="false" data-attr-id="'+uuidv4()+'">'+
                '<td align="center"><input type="hidden" name="product-storage-id[]" value="'+setId+'">'+data[0].value+' <input type="hidden" name="storage[]" value="'+data[0].value+'"><input type="hidden" name="storage_sell[]" value="'+data[0].value+'"></td>'+
                '<td align="right">$'+data[1].value+' <input type="hidden" name="amount[]" value="'+data[1].value+'"></td>'+
                '<td align="center">'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-primary btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-edit"></i>'+
                    '</a>'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-danger btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-trash"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>';
            $('#table-product-sell').append(newRecord);
        }
        $('#modal-product-sell').modal('hide');
    });


    $('.edit-row-product-storage').click(function () {
        $('#isBuyForEdit, #fieldBuyId').val('');
        var inDb = $(this).attr('data-attr-saved');
        var id = $(this).attr('data-attr-id');
        
        if (inDb == "true") 
        {
            $('#isBuyForEdit').val(inDb);
            $('#fieldBuyId').val(id);
            $.ajax({
                type: "GET",
                url: baseUrl+"/admin/products/storage/"+id+"/find",
                dataType: "json",
                success: function (response) {
                    if(response.status == 200){
                        console.log(response.result);
                        $('.input-product-storage').val(response.result.title);
                        $('#excellent_offer').val(response.result.excellent_offer);
                        $('#good_offer').val(response.result.good_offer);
                        $('#fair_offer').val(response.result.fair_offer);
                        $('#poor_offer').val(response.result.poor_offer);
                        $('#modal-product-buy').modal();
                    }
                }
            });
        }
    });

    $('#btn-product-buy').click(function () {
        $('#fieldBuyId').val(0);
        $('#isBuyForEdit').val('');
        $('#modal-product-buy').modal();
    });
    
    $('#product-buy-submit').click(function () {
        var data = $('#product-buy-form').serializeArray();
        var setId = (data[5].value == 0) ? 0 : data[5].value;
        if ($('#isBuyForEdit').val() == "true") {
            var tempId = $('#fieldBuyId').val();
            var newRecord = '<td align="center"><input type="hidden" name="product-storage-id[]" value="'+setId+'">'+data[0].value+' <input type="hidden" name="storage[]" value="'+data[0].value+'"><input type="hidden" name="storage_buy[]" value="'+data[0].value+'"></td>'+
                '<td align="right">$'+data[1].value+' <input type="hidden" name="excellent_offer[]" value="'+data[1].value+'"></td>'+
                '<td align="right">$'+data[2].value+' <input type="hidden" name="good_offer[]" value="'+data[2].value+'"></td>'+
                '<td align="right">$'+data[3].value+' <input type="hidden" name="fair_offer[]" value="'+data[3].value+'"></td>'+
                '<td align="right">$'+data[4].value+' <input type="hidden" name="poor_offer[]" value="'+data[4].value+'"></td>'+
                // '<td align="right">$'+data[6].value+' <input type="hidden" name="amount[]" value="'+data[6].value+'"></td>'+
                '<td align="center">'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-primary btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-edit"></i>'+
                    '</a>'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-danger btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-trash"></i>'+
                    '</a>'+
                '</td>';
                $('.tr-id-'+tempId).attr('data-attr-saved', "true");
                // <tr class="tr-id-'+tempId+'" data-attr-saved="true" data-attr-id="'+tempId+'">'+
                
            $('.tr-id-'+tempId).html(newRecord);
        } else {
            
            var newRecord = '<tr class="tr-id" data-attr-saved="false" data-attr-id="'+uuidv4()+'">'+
                '<td align="center"><input type="hidden" name="product-storage-id[]" value="'+setId+'">'+data[0].value+' <input type="hidden" name="storage[]" value="'+data[0].value+'"><input type="hidden" name="storage_buy[]" value="'+data[0].value+'"></td>'+
                '<td align="right">$'+data[1].value+' <input type="hidden" name="excellent_offer[]" value="'+data[1].value+'"></td>'+
                '<td align="right">$'+data[2].value+' <input type="hidden" name="good_offer[]" value="'+data[2].value+'"></td>'+
                '<td align="right">$'+data[3].value+' <input type="hidden" name="fair_offer[]" value="'+data[3].value+'"></td>'+
                '<td align="right">$'+data[4].value+' <input type="hidden" name="poor_offer[]" value="'+data[4].value+'"></td>'+
                // '<td align="right">$'+data[6].value+' <input type="hidden" name="amount[]" value="'+data[6].value+'"></td>'+
                '<td align="center">'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-primary btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-edit"></i>'+
                    '</a>'+
                    '<a href="javascript:void(0);" class="delete-row-product-storage btn btn-danger btn-xs" data-attr-id="'+uuidv4()+'">'+
                        '<i class="fa fa-trash"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>';
            $('#table-product-buy').append(newRecord);
        }
        $('#modal-product-buy').modal('hide');
    });

    $('#excellent_offer').keyup(function(){
        if($('#offer_type').prop('checked')){
            var good = percent($('#excellent_offer').val(), 'good');
            var fair = percent($('#excellent_offer').val(), 'fair');
            var poor = percent($('#excellent_offer').val(), 'poor');
            $('#good_offer').val(good);
            $('#fair_offer').val(fair);
            $('#poor_offer').val(poor);
        }
    });

    $('#offer_type').change(function() {
        if($(this).prop('checked'))
        {
            var good = percent($('#excellent_offer').val(), 'good');
            var fair = percent($('#excellent_offer').val(), 'fair');
            var poor = percent($('#excellent_offer').val(), 'poor');
            $('#good_offer').val(good);
            $('#fair_offer').val(fair);
            $('#poor_offer').val(poor);
        }
    });

    $('#network').change(function(){
        var data = $('#productForm').serializeArray();
        $.ajax({
            type: "POST",
            url: baseUrl+"/admin/products/checkduplicate",
            data: data,
            dataType: "json",
            success: function (response) {
                if(response.duplicate){
                    $('#network-exist-error').css('display', 'inline');
                }
            }
        });
    });

    $('.input-storage').change(function(){
        var data = $('#productForm').serializeArray();
        $.ajax({
            type: "POST",
            url: baseUrl+"/admin/products/checkduplicate",
            data: data,
            dataType: "json",
            success: function (response) {
                if(response.duplicate){
                    $('#storage-exist-error').css('display', 'inline');
                }else{
                    $('#storage-exist-error').css('display', 'none');
                }
            }
        });
    });

    $('.device-check').change(function() {
            var val = $(this).val();
            if(val == 'Buy') 
            {
                $('#div-offer, .div-offer').show();
                $('#div-amount').hide();
                $('#sku').val('');
            }
            if(val == 'Sell')
            {
                $('#div-offer, .div-offer').hide();
                $('#div-amount').show();
                $('#sku').val(makesku(7));
            }
            if(val == 'Both')
            {
                $('#div-offer, .div-offer').show();
                $('#div-amount').show();
                $('#sku').val(makesku(7));
            }
    });


    // $('#productForm').submit(function () {
        
    //     if ($("#device_type").val() == '' || $("#device_type").val() == null)
    //     {
    //         swal({
    //             title : "Opps!",
    //             text : "Device Type is required",
    //             icon : "warning", 
    //             buttons: "Close",
    //         })
    //         return false;
    //     }

    // });
    
    $('#remove-photo').click(function(){
        if(confirm('Are you sure you want to delete this photo?'))
        {
            var id = $('#product_id').val();
            $.ajax({
                type: "POST",
                url: "{{ url('admin/products/deletephoto') }}",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (response) {
                }
            });
        }
    });


    $( "#brand_id" ).blur(function() {
        if ($(this).val() == '' && $('#product-name').val() != '' && $('#color').val() != '') {
            var validate = validateExistProduct($(this).val(), $('#product-name').val() != '', $('#color').val());
        }
        // alert($(this).val());
    });
    $( "#product-name" ).blur(function() {
        if ($(this).val() != '' && $('#brand_id').val() != '' && $('#color').val() != '') {
            var validate = validateExistProduct($('#brand_id').val(), $(this).val(), $('#color').val());
        }
    });
    $( "#color" ).blur(function() {
        if ($(this).val() != '' && $('#brand_id').val() != '' && $('#product-name').val() != '') {
            var validate = validateExistProduct($('#brand_id').val(), $('#product-name').val(), $(this).val());
        }
    });

    function validateExistProduct(brandId, productName, productColor) 
    {
        $.ajax({
            type: "POST",
            url: baseUrl+"/admin/products/checkduplicatedevice",
            data: {
                'model' : productName, 
                'color' : productColor, 
                'brand_id' : brandId, 
                'product_id' : $('#product_id').val()
            },
            dataType: "json",
            success: function (result) {
                if (result.response == "400") 
                {
                    swal({
                        title : "Opps!",
                        text : result.message,
                        icon : "warning", 
                        buttons: "Close",
                    });
                    // $('#product-name').focus();
                    return false;
                }
                // if(response.duplicate){
                //     $('#storage-exist-error').css('display', 'inline');
                // }else{
                //     $('#storage-exist-error').css('display', 'none');
                // }
            }
        });
    }

}) 

