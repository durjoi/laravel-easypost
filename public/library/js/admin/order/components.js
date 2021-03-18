var baseUrl = $('body').attr('data-url');

$(function () {
    $('.btn-edit-sell-device').on('click', function () {
        $('#selectedId').val('');
        // $('#modal-order-form')[0].reset();
        $('#order-storage-device, #order-network-device').html('');
        var hashedid = $(this).attr('data-attr-id');
        $('#selectedId').val(hashedid);
        $.ajax({
            type: "GET",
            url: baseUrl+"/admin/orders/"+hashedid+"/orderItem",
            dataType: "json",
            success: function (result) {
                $('select[name="product_id"] option[value="' + result.customerSell.product_id + '"]').attr('selected','selected');
                $.each(result.productDetails.storages, function( index, value ) {
                    if (result.customerSell.product_storage.title == value.title) {
                        $('#order-storage-device').append('<option value="'+value.id+'" selected="selected">'+value.title+'</option>');
                    } else {
                        $('#order-storage-device').append('<option value="'+value.id+'">'+value.title+'</option>');
                    }
                });
                
                $.each(result.productDetails.networks, function( index, value ) {
                        $('#order-network-device').append('<option value="'+value.network_id+'">'+value.network.title+'</option>');
                });
                $('#order-quantity-device').val(result.customerSell.quantity);
                $('#order-type-device').val(result.device_type);
                $('#order-type-device option[value="' + result.customerSell.device_type + '"]').attr('selected','selected');
               
            }
        });
        $('#modal-order').modal();
    });

    $('#order-product-device').on('change', function () {
        var id = $(this).val();
        $.ajax({
            type: "GET",
            url: baseUrl+"/api/products/"+id,
            dataType: "json",
            success: function (response) {
                $('#order-storage-device, #order-network-device').html('');
                $.each(response.storages, function( index, value ) {
                    if (response.storages.title == value.title) {
                        $('#order-storage-device').append('<option value="'+value.id+'" selected="selected">'+value.title+'</option>');
                    } else {
                        $('#order-storage-device').append('<option value="'+value.id+'">'+value.title+'</option>');
                    }
                });
                $.each(response.networks, function( index, value ) {
                        $('#order-network-device').append('<option value="'+value.network_id+'">'+value.network.title+'</option>');
                });
            }
        });
    });

    $('#modal-order-form').on('submit', function () {
        var data = $(this).serializeArray();
        form_url = baseUrl+'/api/products/'+$('#selectedId').val();
        if ($('#order-product-device').val() == 0) 
        {
            swalWarning ("Opps!", "Product is required", "warning", "Close");
            return false;
        }
        else if ($('#order-storage-device').val() == '') 
        {
            swalWarning ("Opps!", "Storage is required", "warning", "Close");
            return false;
        }
        else if ($('#order-quantity-device').val() == '' || $('#order-quantity-device').val() == 0) 
        {
            swalWarning ("Opps!", "Quantity is required", "warning", "Close");
            return false;
        }
        else if ($('#order-network-device').val() == '') 
        {
            swalWarning ("Opps!", "Storage is required", "warning", "Close");
            return false;
        }
        else if ($('#order-type-device').val() == '') 
        {
            swalWarning ("Opps!", "Device Condition is required", "warning", "Close");
            return false;
        }
        
        doAjaxProcess('PATCH', '#modal-order-form', data, form_url);
        // console.log($('#order-product-device').val());
        return false;
    });

    $('.btn-delete-sell-device').on('click', function () {
        const hashedId = $(this).attr('data-attr-id');
        var form_url = baseUrl+'/api/order/'+hashedId+'/orderitem';
        doAjaxConfirmProcessing('DELETE', '', {}, form_url);
    });
});

