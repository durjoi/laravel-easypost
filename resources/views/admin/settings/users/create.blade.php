@extends('layouts.app')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Create User</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Create User</li>
          </ol>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-body">
            <form action="{{ url('admin/settings/users/store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @include('admin.settings.users.form')
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('page-js')
{!! JsValidator::formRequest('App\Http\Requests\Admin\UserRequest') !!}
@endsection