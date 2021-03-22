@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>Edit Configuration</h2>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Edit Configuration</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <form action="{{ url('admin/settings/config/1') }}" method="POST">
    @csrf
    @method('put')
    <section class="content">
      <div class="row">
        <div class="col-md-5 fn">
          <div class="config-legend">
            <h4>Site Management</h4>
            <p>Update your company informations and email address</p>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Company Name</label>
                  <input type="text" name="company_name" class="form-control form-control-sm" value="{{ $config->company_name }}" />
                </div>
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Company Email</label>
                  <input type="email" name="company_email" class="form-control form-control-sm" value="{{ $config->company_email }}" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Address 1</label>
                  <input type="text" name="address1" class="form-control form-control-sm" value="{{ $config->address1 }}" />
                </div>
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Address 2 (Optional)</label>
                  <input type="text" name="address2" class="form-control form-control-sm" value="{{ $config->address2 }}" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">City</label>
                  <input type="text" name="city" class="form-control form-control-sm" value="{{ $config->city }}" />
                </div>
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">State</label>
                  {!! Form::select('state', $stateList, $config->state, ['class'=>'custom-select select-sm']) !!}
                </div>
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">Zip</label>
                  <input type="text" name="zip_code" class="form-control form-control-sm" value="{{ $config->zip_code }}" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Phone</label>
                  <input type="text" name="phone" class="form-control form-control-sm" value="{{ $config->phone }}" />
                </div>
                <div class="form-group col-md-6 fn">
                  <label class="col-form-label col-form-label-sm">Company Schedule</label>
                  <input type="text" name="company_schedule" class="form-control form-control-sm" value="{{ $config->company_schedule }}" />
                </div>
              </div>
            </div>
            <div class="card-footer text-muted">
              <div class="float-right">
                <button type="submit" class="btn btn-primary btn-md">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-5 fn">
          <div class="config-legend">
            <h4>Pirce Offer Percentage</h4>
            <p>Update price percentage base on device condition like less 20% if in good condition. Be reminded that the percentage you input will deduct to the original offer.</p>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">Good Condition</label>
                  <input type="number" name="good" class="form-control form-control-sm" value="{{ $config->good }}" />
                </div>
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">Fair Condition</label>
                  <input type="number" name="fair" class="form-control form-control-sm" value="{{ $config->fair }}" />
                </div>
                <div class="form-group col-md-4 fn">
                  <label class="col-form-label col-form-label-sm">Poor Condition</label>
                  <input type="number" name="poor" class="form-control form-control-sm" value="{{ $config->poor }}" />
                </div>
              </div>
            </div>
            <div class="card-footer text-muted">
              <div class="float-right">
                <button type="submit" class="btn btn-primary btn-md">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
@endsection

@section('page-css')
<style>
.fn label {
  font-weight: normal !important;
}
.fn p {
  font-size: .875rem;
}
.config-legend {
  margin-left: 9px;
}
</style>
@endsection