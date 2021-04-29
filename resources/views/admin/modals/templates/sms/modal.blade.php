
<style>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>

<div class="modal fade" id="modal-smstemplate" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span class="modal-header-smstemplate-action"></span> SMS Template</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="modal-smstemplate-form">
                <div class="modal-body">
                    <div class="card-body padding0">
                        <div class="form-group">
                            <input name="name" id="modal_smstemplate_name" data-attr="Template Name" class="form-control form-control-sm" value="" placeholder="Template Name:">
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" id="modal_smstemplate_receiver" name="receiver" data-attr="Reciever">
                                        <option value="">Select Receiver</option>
                                        <option value="Customer">Customer</option>
                                        <option value="TronicsPay">TronicsPay</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control form-control-sm" id="modal_smstemplate_status" name="status" data-attr="Template Status">
                                        <option value="">Template Status</option>
                                        <option value="Active">Active</option>
                                        <option value="In-Active">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control  form-control-sm" id="modal_smstemplate_model" name="model" data-attr="Module">
                                        <option value="">Select Module</option>
                                        <option value="Registration">Registration</option>
                                        <option value="Orders">Orders</option>
                                        <option value="Products">Products</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="content" rows="4" cols="" class="form-control form-control-sm" id="modal_smstemplate_content" placeholder="Template Message:" maxlength="160" data-attr="Message"></textarea>
                            <div id="modal_smstemplate_content_counter"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="callout callout-warning">
                                        <h8 style="font-weight: bold;"><i class="fas fa-info"></i> Note:</h8>
                                        Use placeholder below to fill up the details automatically.
                                    </div>
                                        <div class="card card-outline card-warning collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fas fa-user"></i> Customer Details</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <li>
                                                    Customer Name : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 121px;" readonly id="clipboard_customer_name" value="{customer_name}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_customer_name" onClick="copyInputToClipBoard('clipboard_customer_name')"><i class="fas fa-copy"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Username : 
                                                    <input class="text-yellow" style="background: none; border: 0; " readonly id="clipboard_customer_username" value="{customer_username}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_customer_username" onClick="copyInputToClipBoard('clipboard_customer_username')"><i class="fas fa-copy"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Email Address : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 132px;" readonly id="clipboard_customer_email" value="{customer_email}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_customer_email" onClick="copyInputToClipBoard('clipboard_customer_email')"><i class="fas fa-copy"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Password : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 158px;" readonly id="clipboard_customer_password" value="{customer_password}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_customer_password" onClick="copyInputToClipBoard('clipboard_customer_password')"><i class="fas fa-copy"></i> Copy</a>
                                                </li>
                                            </div>
                                        </div> 
                                        <div class="card card-outline card-warning collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title"><i class="fas fa-people-carry"></i> Order Details</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <li>
                                                    Shipping Label : 
                                                    <input class="text-yellow" style="background: none; border: 0; " readonly id="clipboard_order_shipping_label" value="{order_shipping_label}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_order_shipping_label" onClick="copyInputToClipBoard('clipboard_order_shipping_label')"><i class="fas fa-copy fa-fw"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Tracking # : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 183px;" readonly id="clipboard_order_tracking_number" value="{order_tracking_number}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_order_tracking_number" onClick="copyInputToClipBoard('clipboard_order_tracking_number')"><i class="fas fa-copy fa-fw"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Transaction ID : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 158px;" readonly id="clipboard_order_transaction_id" value="{order_transaction_id}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_order_transaction_id" onClick="copyInputToClipBoard('clipboard_order_transaction_id')"><i class="fas fa-copy fa-fw"></i> Copy</a>
                                                </li>
                                                <li>
                                                    Status : 
                                                    <input class="text-yellow" style="background: none; border: 0; width: 205px;" readonly id="clipboard_order_status" value="{order_status}"> 
                                                    <a href="javascript:void(0);" id="tooltip_button_clipboard_clipboard_order_status" onClick="copyInputToClipBoard('clipboard_order_status')"><i class="fas fa-copy fa-fw"></i> Copy</a>
                                                </li>
                                            </div>
                                        </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" name="id" id="modal_smstemplate_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="sms-submit">Save changes</button>
                </div>      
            </form>
        </div>
    </div>
</div>