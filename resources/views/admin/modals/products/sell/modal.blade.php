<div class="modal fade" id="modal-product-sell" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-product-title">Device Storage Prices</h4>
            </div>
            <form role="form" method="POST" id="product-sell-form">
                <div class="modal-body">
                    <div class="card-body">
                        <div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="col-form-label col-form-label-sm">Storage</label>
                                    {!! Form::select('storage', $storageList, '', ['class'=>'custom-select select-sm input-product-storage']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="col-form-label col-form-label-sm">Device Price</label>
                                    <input type="number" name="amount" id="amount" class="form-control form-control-sm" value="{{ isset($product->amount) ? $product->amount : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <div class="modal-footer">
                    <div class="float-right">
                        <input type="hidden" id="isSellForEdit" value="">
                        <input type="hidden" id="fieldSellId" name="hashedid">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="product-sell-submit">Save changes</a>
                    </div> 
                </div>   
            </form>
        </div>
    </div>
</div>