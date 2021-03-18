@extends('layouts.front')
@section('content')
<div class="pt-70">
  <div class="container pt-70">
    <div class="row">
      <div class="col-lg-7">
        <div class="card">
          <div class="card-body">
            <form action="{{ url('customer/auth/register') }}" method="POST">
              @csrf
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">First Name</label>
                  <input type="text" name="fname" class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">Last Name</label>
                  <input type="text" name="lname" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">Email Address</label>
                  <input type="email" name="email" class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">Username</label>
                  <input type="text" name="username" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">Password</label>
                  <input type="password" name="password" class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-6">
                  <label class="col-form-label col-form-label-sm">Retype Password</label>
                  <input type="password" name="password_confirmation" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label class="col-form-label col-form-label-sm">Street (Address)</label>
                  <input type="text" name="street" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label class="col-form-label col-form-label-sm">City</label>
                  <input type="text" name="city" class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-4">
                  <label class="col-form-label col-form-label-sm">State</label>
                  <input type="text" name="state" class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-4">
                  <label class="col-form-label col-form-label-sm">Zip Code</label>
                  <input type="text" name="zip" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label class="col-form-label col-form-label-sm">Phone</label>
                  <input type="text" name="phone" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group">
                <input type="hidden" name="cart" value="{{ $cartcount }}">
                <button type="submit" class="btn btn-warning btn-md">Register</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        
      </div>
    </div>
  </div>
</div>
@endsection