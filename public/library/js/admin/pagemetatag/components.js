var baseUrl = $('body').attr('data-url');

$(function () {
    
    $('#create-meta-tags').click(function (){
        $('#modal-metatags-form')[0].reset();
        $('#modal-metatags').modal();
        // getDropdownOptionsTitleId(baseUrl+'/api/pagebuilders', 'GET', 'modal_dropdown_metatag_pageid');
        // getDropdownOptionsStatic(baseUrl+'/api/metatagnames', 'GET', 'modal_dropdown_metatag_name');
    });

    $('#modal-metatags-form').on('submit', function () {
        var data = $(this).serializeArray();
        var form_url = baseUrl+'/api/page/metatags';
        doAjaxProcess('PATCH', '#modal-metatags-form', data, form_url);
        return false;
    });

    if($("#metatags-table").length)
    {
        var categoryTable;
        categoryTable = $('#metatags-table').DataTable({
            processing: true,
            serverSide: true,
            "pagingType": "input",
            ajax: {
                url: baseUrl+'/api/page/metatags',
                type:'POST'
            },
            columns: [
                {
                    width:'5%', searchable: false, orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }, className: "text-center"
                },
                { data: 'name', name: 'name', searchable: true, orderable: true, width:'25%' },
                { data: 'page', name: 'page', searchable: true, orderable: true, width:'25%' },
                { data: 'content', name: 'content', searchable: true, orderable: true, width:'35%' },
                { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center"},
            ]
        });
    }
});



function updatemetatag (hashedId) 
{
    $('#modal-metatags-form')[0].reset();
    $('#modal_page_metatag_id').val(hashedId);
	$.ajax({
		url: baseUrl+'/api/page/metatags/'+hashedId,
		type: "GET",
		dataType: 'json',
		success: function (result) 
		{
            if (result.status == 200) 
            {
                $('#modal_dropdown_metatag_pageid option[value="' + result.model.page_id + '"]').attr('selected','selected');
                $('#modal_dropdown_metatag_name option[value="' + result.model.name + '"]').attr('selected','selected');
                $('#modal_metatag_content').val(result.model.content);
                $('#modal-metatags').modal();
            } else {
                swalWarning ("Oops", "Category not found", "warning", "Close");
                return false;
            }
		}
	});
}

function deletemetatag (hashedId) 
{
    var form_url = baseUrl+'/api/page/metatags/'+hashedId;
    doAjaxConfirmProcessing('DELETE', '', {}, form_url);
}

