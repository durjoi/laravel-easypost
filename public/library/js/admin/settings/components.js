var baseUrl = $('body').attr('data-url');

$(function () {
    

    $('#create-status').click(function (){
        $('#modal-status-form')[0].reset();
        $('#modal-status').modal();
        getDropdownOptionsStatic(baseUrl+'/api/modules', 'GET', 'modal_dropdown_module');
        // getDropdownOptionsStatic(baseUrl+'/api/enableoptions', 'GET', 'modal_dropdown_enableoptions');
    });

    $('#modal-status-form').on('submit', function () {
        var data = $(this).serializeArray();
        var form_url = baseUrl+'/api/settings/status';
        doAjaxProcess('PATCH', '#modal-status-form', data, form_url);
        return false;
    });

    $('#modal_dropdown_module').on('change', function () 
    {
        if ($(this).val() == "Order") 
        {
            getDropdownOptionsStatic(baseUrl+'/api/enableoptions', 'GET', 'modal_dropdown_enableoptions');
            $('#modal_div_emailsending').removeClass('hideme');
        }
        else 
        {
            $('#modal_dropdown_enableoptions').html('');
            $('#modal_div_emailsending').addClass('hideme');
        }
    }); 


    $('#create-category').click(function (){
        $('#modal-category-form')[0].reset();
        $('#modal-category').modal();
        // getDropdownOptionsStatic(baseUrl+'/api/modules', 'GET', 'modal_dropdown_module');
        // getDropdownOptionsStatic(baseUrl+'/api/enableoptions', 'GET', 'modal_dropdown_enableoptions');
    });

    $('#modal-category-form').on('submit', function () {
        var data = $(this).serializeArray();
        var form_url = baseUrl+'/api/settings/categories';
        doAjaxProcess('PATCH', '#modal-category-form', data, form_url);
        return false;
    });


});

function editStatus (hashedId) 
{
    $('#modal-status-form')[0].reset();
    getDropdownOptionsStatic(baseUrl+'/api/modules', 'GET', 'modal_dropdown_module');
    getDropdownOptionsStatic(baseUrl+'/api/enableoptions', 'GET', 'modal_dropdown_enableoptions');
    $('#modal_status_id').val(hashedId);
	$.ajax({
		url: baseUrl+'/api/settings/status/'+hashedId,
		type: "GET",
		dataType: 'json',
		success: function (result) 
		{
            if (result.status == 200) 
            {
                $('#summernote').summernote('reset');
                $('select[name="module"] option[value="' + result.model.module + '"]').attr('selected','selected');
                $('#modal_status').val(result.model.name);
                if (result.model.module == "Order") {
                    $('#modal_div_emailsending').removeClass('hideme');
                }
                if (result.model.email_sending == "Disable" || result.model.module != "Order") {
                    $('#summernote').summernote('reset');
                }
                $('select[name="email_sending"] option[value="' + result.model.email_sending + '"]').attr('selected','selected');
                if (result.model.template != "") {
                    $('#summernote').summernote('pasteHTML', result.model.template);
                }
                
                $('#modal-status').modal();
            } else {
                swalWarning ("Oops", "Status not found", "warning", "Close");
                return false;
            }
		}
	});
}

function deleteStatus (hashedId) 
{
    var form_url = baseUrl+'/api/settings/status/'+hashedId;
    doAjaxConfirmProcessing('DELETE', '', {}, form_url);
}


function editCategory (hashedId) 
{
    $('#modal-category-form')[0].reset();
    $('#modal_category_id').val(hashedId);
	$.ajax({
		url: baseUrl+'/api/settings/categories/'+hashedId,
		type: "GET",
		dataType: 'json',
		success: function (result) 
		{
            if (result.status == 200) 
            {
                $('#modal_category_name').val(result.model.name);
                $('#modal-category').modal();
            } else {
                swalWarning ("Oops", "Category not found", "warning", "Close");
                return false;
            }
		}
	});
}

function deleteCategory (hashedId) 
{
    var form_url = baseUrl+'/api/settings/categories/'+hashedId;
    doAjaxConfirmProcessing('DELETE', '', {}, form_url);
}
