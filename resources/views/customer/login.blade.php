@extends('layouts.front')
@section('content')
<div class="pt-70">
  <div class="container pt-70">
    <div class="row">
      <div class="col-lg-7">
        <h3>Member Login</h3>
        <div class="card">
          <div class="card-body">
            <form action="{{ url('customer/auth/login') }}" method="POST">
              @csrf
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label class="col-form-label col-form-label-sm">Username</label>
                  <input type="text" name="email" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label class="col-form-label col-form-label-sm">Password</label>
                  <input type="password" name="password" class="form-control form-control-sm">
                </div>
              <div class="form-group">
                <input type="hidden" name="activecart" value="{{ (session()->has('activecart')) ? session('activecart') : '' }}">
                <button type="submit" class="btn btn-warning btn-md">Login</button>
                <a href="{{ url('customer/auth/register') }}" class="btn btn-outline-warning btn-md">Register</a>
              </div>
            </form>
            @if(session()->has('msg'))
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{ session('msg') }}
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        
      </div>
    </div>
  </div>
</div>
@endsection