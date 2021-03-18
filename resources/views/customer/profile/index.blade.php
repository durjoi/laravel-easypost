@extends('layouts.customer')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/customer/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile Details</h3>
            </div>
            <div class="card-body">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                        <div class="col-md-3">

                            <div class="card card-warning card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle" src="{{ url('/').'/assets/dist/img/user4-128x128.jpg' }}" alt="User profile picture">
                                    </div>

                                    <h3 class="profile-username text-center">{{ $user['fname'].' '.$user['lname'] }}</h3>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Devices</b> <a class="float-right">1,322</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Orders</b> <a class="float-right">543</a>
                                        </li>
                                    </ul>

                                    <a href="javascript:void(0);" id="profile-change-password" data-attr-id="{{ $user['hashedid']}}" class="btn btn-warning btn-block"><b>Change Password</b></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#userdetails" data-toggle="tab">User Summary</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#paymentdetails" data-toggle="tab">Payment Details</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="userdetails">
                                            <form class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">First Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="profile-input-fname" placeholder="First Name" value="{{ $user['fname'] }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputName" class="col-sm-2 col-form-label">Last Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="profile-input-lname" placeholder="Last Name" value="{{ $user['lname'] }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="profile-input-emaik" placeholder="Email" value="{{ $user['email'] }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button type="submit" class="btn btn-warning">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane" id="paymentdetails">
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <select class="custom-select select-sm" id="payment_method" name="payment_method">
                                                        <option value="" selected="selected">--</option>
                                                        <option value="Apple Pay">Apple Pay</option>
                                                        <option value="Google Pay">Google Pay</option>
                                                        <option value="Venmo">Venmo</option>
                                                        <option value="Cash App">Cash App</option>
                                                        <option value="Paypal">Paypal</option>
                                                        <option value="Bank Transfer">Bank Transfer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>







            </div>
        </div>
    </section>
</div>
@endsection
@section('page-js')
    @include('customer.modals.profile.changepassword.modal')
@endsection