var baseUrl = $('body').attr('data-url');

function changepasswordcustomer (hashedId) 
{
    $('#modal_customer_id').val('');
    $('#modal_customer_id').val(hashedId);
    $('#modal-changepassword').modal();
}
$(function () {
    // alert('asd');
    // $('.customer-change-password').on('click', function () {
    //     // $('#modal_customer_id').val('');
    //     // const id = $(this).attr('data-attr-id');
    //     // $('#modal_customer_id').val(id);
    //     // $('#modal-changepassword').modal();
    //     alert('asd');
    // });

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

});