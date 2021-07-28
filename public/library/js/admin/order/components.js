// const { ajax } = require("jquery");

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
        var form_url = baseUrl+'/api/orders/'+hashedId+'/orderitem';
        doAjaxConfirmProcessing('DELETE', '', {}, form_url);
    });
    $('.approve-order').on('click', function () {
        var id = $(this).attr('data-attr-id');
        alert(id);
    });
    $('.confirm-pay-approval').on('click', function () {
        $.ajax({
            type: "GET",
            url: baseUrl+"/api/admin/payment",
            dataType: "json",
            success: function (response) {
                // $('#modal-approve-order').modal();
                // if (response.status == 200) {
                //     $('#approve-payment-image').html(response.payment);
                //     $('#selectedForApproveOrderId').val(id);
                // }
            }
        });
    });

    if ($('#modal-status-order-form').length) 
    {
        $('#modal-status-order-form').on('submit', function () {
            var data = $(this).serializeArray();
            $('.modal-button-order-status-id').addClass('disabled');
            $('.modal-button-order-status-id').html('<i class="fas fa-spinner fa-spin"></i> Please wait...');
            form_url = baseUrl+'/api/orders/'+$('#selectedStatusId').val()+'/status';
            doAjaxProcess('PUT', '.modal-status-order-form', data, form_url);
            $('.modal-button-order-status-id').removeClass('disabled');
            return false;
        })
    }

    $('.modal-order-status-id').on('change', function () {
        // alert($(this).val());
        if ($(this).val() == 6 || $(this).val() == 10) {
            $('.modal-status-template-sms').removeClass('hideme');
            
            $.ajax({
                type: "GET",
                url: baseUrl+"/api/templates/sms",
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                        $.each(response.model, function( index, value ) {
                            $('#modal-status-select-template-sms').append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                }
            });
        } else {
            
            $('.modal-status-template-sms').addClass('hideme');
            $('#modal-status-select-template-sms').html('<option value="">Please Select SMS Template</option>');
        }
    });

    $('#modal-note-order-form').on('submit', function () {
        var data = $(this).serializeArray();
        form_url = baseUrl+'/api/orders/notes';
        OpenPreloaderModal('modal-note-order-preloader');
        doAjaxProcess('POST', '#modal-note-order-form', data, form_url);
        return false;
    });
});

function UpdateOrderStatus (id, statusId) {
    $('.modal-order-status-id').html('');
    $('#selectedStatusId').val('');
    $('.modal-button-order-status-id').html('Update');
    $('.modal-status-template-sms').addClass('hideme');
    $('#modal-status-select-template-sms').html('<option value="">Please Select SMS Template</option>');
    $.ajax({
        type: "GET",
        url: baseUrl+"/api/settings/status/filter/order",
        dataType: "json",
        success: function (response) {
            $('#modal-status-order').modal();
            if (response.status == 200) {
                $('#selectedStatusId').val(id);
                $.each(response.model, function( index, value ) {
                    if (statusId == value.id) {
                        $('.modal-order-status-id').append('<option value="'+value.id+'" selected="selected">'+value.name+'</option>');
                    } else {
                        $('.modal-order-status-id').append('<option value="'+value.id+'">'+value.name+'</option>');
                    }
                });
            }
        }
    });
}

function ApproveOrder (id) {
    var baseUrl = $('body').attr('data-url');
    $('#approve-payment-image').html('');
    $('#selectedForApproveOrderId').val('');
    $('#paypal-payment').addClass('hideme');
    var qtyItem = 0;
    var overallTotalAmount = 0;
    $.ajax({
        type: "GET",
        url: baseUrl+"/api/orders/"+id,
        dataType: "json",
        success: function (response) {
            $('#modal-approve-order').modal();
            if (response.status == 200) {
                $('#selectedForApproveOrderId').val(id);
                if (response.payment == 'Paypal') {
                    $('.paypal-payment').removeClass('hideme');
                    $('#approve-payment-image').html('');
                    $.each(response.order.order_item, function( index, value ) {
                        var total = value.amount * value.quantity;
                        overallTotalAmount = overallTotalAmount + total;
                        qtyItem = qtyItem + value.quantity;
                    });
                    initPayPalButton(overallTotalAmount);
                    // initPayPalButton(overallTotalAmount, qtyItem, response.order.shipping_fee);
                } else {
                    $('.paypal-payment').addClass('hideme');
                    $('#approve-payment-image').html(response.payment_image);
                }
            }
        }
    });
}

function AddOrderNotes (hashedId) 
{
    $('#selectedForNotesOrderId').val(hashedId);
    $('#modal-note-order').modal();
}

function OpenOrderNotes (hashedId) 
{
    OpenPreloader ('modal-order-notes-list-preloader');
    $('#modal-order-notes-list').html('');
    $.ajax({
        type: "GET",
        url: baseUrl+"/api/orders/"+hashedId+"/notes",
        dataType: "json",
        success: function (response) {
            if (response.status == 200) 
            {
                $.each(response.model, function( index, value ) {

                    var displayNotes = '<div id="modal-order-notes-div-'+value.hashedid+'" class="card" style="border: 1px  solid #dee2e6;">'+
                            '<div class="card-header" style="background: #f8f9fa; color: #495057;">'+
                                '<div class="card-tools">'+
                                    '<a href="javascript:void(0);" class="btn btn-sm btn-tool font12px" onClick="modalEditNotes(\''+value.hashedid+'\')">'+
                                        '<i class="fas fa-edit"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" class="btn btn-sm btn-tool font12px" onClick="modalDeleteNotes(\''+value.hashedid+'\')">'+
                                        '<i class="fas fa-trash"></i>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                            '<div class="card-body" style="background: #f8f9fa; color: #495057;">'+
                                '<div id="modal-order-notes-'+value.hashedid+'">'+value.notes+'</div>'+
                                '<div id="modal-order-notes-edit-'+value.hashedid+'"></div>'+
                                '<div id="modal-order-notes-date-'+value.hashedid+'" class="pull-right font12px">'+
                                    '<b><i>Date Posted: </i></b> <i class="fas fa-calendar"></i> <i>'+value.display_created_at+'</i>'+
                                '</div>'+
                            '</div>'+
                            '<div class="modal-order-notes-preloader-'+value.hashedid+'"></div>'+
                        '</div>';

                    $('#modal-order-notes-list').append(displayNotes);
                    $('#order-storage-device').append('<option value="'+value.id+'" selected="selected">'+value.title+'</option>');
                });
                ClosePreloader ('modal-order-notes-list-preloader');
            }
        }
    });
    $('#modal-note-list-order').modal();
}

function modalEditNotes (hashedId) 
{
    $('#modal-order-notes-'+hashedId).addClass('hideme');
    $('#modal-order-notes-date-'+hashedId).addClass('hideme');
    var noteValue = $('#modal-order-notes-'+hashedId).html();

    var generateNoteField = '<textarea id="modal-order-notes-textfield-'+hashedId+'" rows="4" class="form-control form-control-sm">'+noteValue+'</textarea>';
    generateNoteField += '<button type="button" class="btn btn-sm btn-primary pull-right" onClick="UpdateOrderNotes(\''+hashedId+'\')">Update Note</button>';
    $('#modal-order-notes-edit-'+hashedId).html(generateNoteField);
    return false;
}

function UpdateOrderNotes (hashedId) 
{
    OpenPreloaderModal ('modal-order-notes-preloader-'+hashedId);
    var data = {
        'hashedid' : hashedId,
        'notes' : $('#modal-order-notes-textfield-'+hashedId+'').val()
    }
    var form_url = baseUrl+"/api/orders/"+hashedId+"/notes";
    doAjaxProcess('PUT', '', data, form_url);
    $('#modal-order-notes-'+hashedId).html($('#modal-order-notes-textfield-'+hashedId+'').val());
    ClosePreloader ('modal-order-notes-preloader-'+hashedId);
    $('#modal-order-notes-edit-'+hashedId).html('');
    $('#modal-order-notes-'+hashedId).removeClass('hideme');
    $('#modal-order-notes-date-'+hashedId).removeClass('hideme');
}

function modalDeleteNotes (hashedId)
{
    var form_url = baseUrl+"/api/orders/"+hashedId+"/notes";
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this data!",
		icon: "warning",
		// buttons: true,
		buttons: ["No", "Yes"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			doAjaxProcess('DELETE', '', {}, form_url);
            $('#modal-order-notes-div-'+hashedId).html('');
            $('#modal-order-notes-div-'+hashedId).attr('style', '');
		}
	});
}

function deleteOrder (hashedId)
{
    var form_url = baseUrl+"/admin/orders/"+hashedId+"/delete";
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this data!",
		icon: "warning",
		// buttons: true,
		buttons: ["No", "Yes"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			// doAjaxProcess('DELETE', '', {}, form_url);
            $.ajax({
            url: baseUrl+"/admin/orders/"+hashedId+"/delete",
            type: 'DELETE',
            success: function (data) {
                if (data.status == 200) {
                    // toastr.success('Product has been deleted!')
                    swal({
                        title : "Order has been deleted!",
                        text : data.message,
                        icon : "success", 
                    }).then(() => {
                        setTimeout(() => {
                            location.reload();
                        }, 0);
                    });

                } else {
                    swal(
                        'Something went wrong!',
                        `${data.message}`,
                        'error',
                    );
                }
            }
        });
            // $('#modal-order-notes-div-'+hashedId).html('');
            // $('#modal-order-notes-div-'+hashedId).attr('style', '');
		}
	});
}