<div class="row">
  <div class="col-md-4 fn">
    <div class="config-legend">
      <h4>Device Information</h4>
      <p>Input all information about the device</p>
    </div>
  </div>
  <div class="col-md-8">
    <div class="form-group">
      <div class="form-check form-check-inline">
        <input class="form-check-input device-check" type="radio" name="device_type" id="device_type1" value="Buy" {{ (isset($product->device_type) && $product->device_type == 'Buy') ? 'checked' : '' }}>
        <label class="form-check-label" for="device_type1">I want to buy a device</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input device-check" type="radio" name="device_type" id="device_type2" value="Sell" {{ (isset($product->device_type) && $product->device_type == 'Sell') ? 'checked' : '' }}>
        <label class="form-check-label" for="device_type2">I want to sell a device</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input device-check" type="radio" name="device_type" id="device_type3" value="Both" {{ (isset($product->device_type) && $product->device_type == 'Both') ? 'checked' : '' }}>
        <label class="form-check-label" for="device_type3">I want to buy and sell a device</label>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label class="col-form-label col-form-label-sm">Brand Name</label>
            {!! Form::select('brand_id', $brandList, isset($product->brand_id) ? $product->brand_id : '', ['class'=>'custom-select select-sm','id'=>'brand_id']) !!}
          </div>
          <div class="form-group col-md-4">
            <label class="col-form-label col-form-label-sm">Model</label>
            <input type="text" name="name" id="product-name" class="form-control form-control-sm" placeholder="Enter model" value="{{ isset($product->model) ? $product->model : '' }}">
          </div>
          <div class="form-group col-md-4">
            <label class="col-form-label col-form-label-sm">Color</label>
            <input type="text" name="color" class="form-control form-control-sm" id="color" value="{{ isset($product->color) ? $product->color : '' }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Height (inches)</label>
            <input type="number" name="height" id="height" class="form-control form-control-sm" value="{{ isset($product->height) ? $product->height : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Width (inches)</label>
            <input type="number" name="width" id="width" class="form-control form-control-sm" value="{{ isset($product->width) ? $product->width : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Length (inches)</label>
            <input type="text" name="length" class="form-control form-control-sm" id="length" value="{{ isset($product->length) ? $product->length : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Weight (ounces)</label>
            <input type="number" name="weight" id="weight" class="form-control form-control-sm" value="{{ isset($product->weight) ? $product->weight : '' }}">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label class="col-form-label col-form-label-sm">Device Photo</label>
            <div style="<?php echo isset($product->photo) ? 'display:none' : ''; ?>">
              <input type="file" name="photo">
            </div>
            <div style="width: 50%;<?php echo isset($product->photo) ? 'display:block' : 'display:none'; ?>">
              <div class="input-group mb-3">
                <input type="text" class="form-control form-control-sm" aria-describedby="remove-photo" value="{{ isset($product->photo) ? $product->photo->photo_display : '' }}" readonly>
                <div class="input-group-append">
                  <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm" id="remove-photo"><i class="fas fa-trash-alt"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Network</label>
            {!! Form::select('network', $networkList, isset($product->network) ? $product->network : '', ['class'=>'custom-select select-sm','id'=>'network']) !!}
            <span id="network-exist-error" class="invalid-feedback" style="display: none;">This device is already exist in the database.</span>
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Storage</label>
            {!! Form::select('storage', $storageList, isset($product->storage) ? $product->storage : '', ['class'=>'custom-select select-sm']) !!}
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-12">
            <label class="col-form-label col-form-label-sm">Description</label>
            <textarea name="description" id="description" class="textarea">{{ isset($product->description) ? $product->description : '' }}</textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<div class="row" id="div-offer" style="<?php echo (isset($product->device_type) && in_array($product->device_type, ['Buy','Both'])) ? '' : 'display: none'; ?>">
  <div class="col-md-4 fn">
    <div class="config-legend">
      <h4>Price Offer</h4>
      <p>Input a price offer depends on the condition</p>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="offer_type" id="offer_type" value="1" {{ (isset($product->offer_type) && $product->offer_type == 1) ? 'checked' : '' }}>
          <label class="form-check-label" for="offer_type">Auto generate offer base on the percentage</label>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Excellent Offer</label>
            <input type="number" name="excellent_offer" id="excellent_offer" class="form-control form-control-sm" value="{{ isset($product->excellent_offer) ? $product->excellent_offer : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Good Offer</label>
            <input type="number" name="good_offer" id="good_offer" class="form-control form-control-sm" value="{{ isset($product->good_offer) ? $product->good_offer : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Fair Offer</label>
            <input type="number" name="fair_offer" id="fair_offer" class="form-control form-control-sm" value="{{ isset($product->fair_offer) ? $product->fair_offer : '' }}">
          </div>
          <div class="form-group col-md-3">
            <label class="col-form-label col-form-label-sm">Poor Offer</label>
            <input type="number" name="poor_offer" id="poor_offer"class="form-control form-control-sm" value="{{ isset($product->poor_offer) ? $product->poor_offer : '' }}">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<div class="row" id="div-amount" style="<?php echo (isset($product->device_type) && in_array($product->device_type, ['Sell','Both'])) ? '' : 'display: none'; ?>">
  <div class="col-md-4 fn">
    <div class="config-legend">
      <h4>Device Price</h4>
      <p>Input a price for the device you are selling</p>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <div class="form-group col-md-3">
          <label class="col-form-label col-form-label-sm">Device Price</label>
          <input type="number" name="amount" id="amount" class="form-control form-control-sm" value="{{ isset($product->amount) ? $product->amount : '' }}">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row pb-50">
  <div class="col-md-12 fn">
    <div class="text-center">
      <input type="hidden" name="sku" class="form-control form-control-sm" id="sku" value="{{ isset($product->sku) ? $product->sku : '' }}">
      <input type="hidden" name="id" value="{{ isset($product->id) ? $product->id : '' }}">
      <button type="submit" class="btn btn-primary btn-md">Save Changes</button>
    </div>
  </div>
</div>