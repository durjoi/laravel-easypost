var baseUrl = $('body').attr('data-url');

function changepasswordcustomer (hashedId) 
{
    $('#modal_customer_id').val('');
    $('#modal_customer_id').val(hashedId);
    $('#modal-changepassword').modal();
}
$(function () {

    $('#modal-profile-form').on('submit', function () {
        const data = $(this).serializeArray();
        if ($.trim($('#new-password').val()) == '') {
            swalWarning ('Oops', 'New Password is required', 'warning', 'Close');
            return false;
        }
        if ($.trim($('#new-password').val()).length <= 5) {
            swalWarning ('Oops', 'New password must be atleast 6 characters', 'warning', 'Close');
            return false;
        }
        if ($.trim($('#retype-password').val()) == '') {
            swalWarning ('Oops', 'Re-type Password is required', 'warning', 'Close');
            return false;
        }
        if ($('#new-password').val() != $('#retype-password').val()) {
            swalWarning ('Oops', 'New Password and Re-type Password not matched', 'warning', 'Close');
            return false;
        }
        const form_url = baseUrl+'/api/admin/customers/changepassword';
        doAjaxProcess('PATCH', '#modal-profile-form', data, form_url);
        return false;
    });


    if($("#customer-table").length)
    {
        customerTable = $('#customer-table').DataTable({
            processing: true,
            serverSide: true,
            "pagingType": "input",
            ajax: {
                url: baseUrl+'/api/admin/customers',
                type:'POST'
            },
            columns: [
                {
                    width:'2%', searchable: false, orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }, 
                    className: "text-center"
                },
                { data: 'fullname', name: 'fullname', searchable: true, orderable: true, width:'20%' },
                { data: 'email', name: 'email', searchable: true, orderable: true, width:'20%' },
                { data: 'phone', name: 'phone', searchable: true, orderable: true, width:'15%' },
                { data: 'address', name: 'address', searchable: true, orderable: true, width:'35%' },
                { data: 'action', name: 'action', searchable: false, orderable: false, width:'8%', className: "text-center" },
            ]
        });
    }

});