<div class="modal fade" id="modal-product-buy" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-product-title">Device Storage Prices</h4>
            </div>
            <form role="form" method="POST" id="product-buy-form">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class="col-form-label col-form-label-sm">Storage</label>
                                {!! Form::select('storage', $storageList, '', ['class'=>'custom-select select-sm input-product-storage']) !!}
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                        <!-- name="offer_type" -->
                            <input class="form-check-input" type="checkbox" id="offer_type" value="1" {{ (isset($product->offer_type) && $product->offer_type == 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="offer_type">Auto generate offer base on the percentage</label>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="col-form-label col-form-label-sm">Excellent Offer</label>
                                <input type="number" name="excellent_offer" id="excellent_offer" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label col-form-label-sm">Good Offer</label>
                                <input type="number" name="good_offer" id="good_offer" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label col-form-label-sm">Fair Offer</label>
                                <input type="number" name="fair_offer" id="fair_offer" class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-form-label col-form-label-sm">Poor Offer</label>
                                <input type="number" name="poor_offer" id="poor_offer"class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="alert alert-danger" id="exist-error" role="alert" style="display: none;">
                        This type of device already exists!
                    </div>
                </div>  
                <div class="modal-footer">
                    <div class="float-right">
                        <input type="hidden" id="isBuyForEdit" value="">
                        <input type="hidden" id="fieldBuyId" name="hashedid">
                        <input type="hidden" name="sku" class="form-control form-control-sm" id="sku" value="{{ isset($product->sku) ? $product->sku : '' }}">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" id="product-buy-submit">Save changes</a>
                    </div> 
                </div>   
            </form>
        </div>
    </div>
</div>