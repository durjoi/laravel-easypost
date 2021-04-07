<div class="modal fade" id="modal-status-order" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Order Status</h4>
            </div>
            <form role="form" method="POST" id="modal-status-order-form">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Change Status to:</label>
                                <select name="status_id" class="custom-select select-sm modal-order-status-id"></select>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="modal-footer">
                    <div class="float-right">
                        <input type="hidden" id="selectedStatusId" name="hashedid">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm modal-button-order-status-id">
                            Update
                        </button>
                    </div> 
                </div>   
            </form>
        </div>
    </div>
</div>

@if(isset($paypal))
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal['paypal_account_client_id'] }}&currency=USD" data-sdk-integration-source="button-factory"></script>
@endif

<!-- <script>
    initPayPalButton();
</script> -->