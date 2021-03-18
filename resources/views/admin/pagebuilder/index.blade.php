@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Page Builder</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Page Builder</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <a href="javascript:void(0);" id="create-page" class="btn btn-primary btn-sm">Create Page</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover text-nowrap table-sm">
                        <thead>
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 40%;">Title</th>
                                <th style="width: 40%;">URL/LINK</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($pageBuilder))
                                @forelse($pageBuilder as $key => $page)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->url }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="{{ '../'.$page->url }}" target="_blank" class="btn btn-light btn-sm">
                                                    <span>View</span> <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm edit-page" data-attr-identification="{{ $page->id }}" data-attr-id="{{ $page->hashedid }}">
                                                    <span>Edit</span> <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ url('admin/pagebuilder/'.$page->hashedid.'/build') }}" class="btn btn-secondary btn-sm">
                                                    <span>Settings</span> <i class="fas fa-cog"></i>
                                                </a>
                                                <!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm">
                                                <span>Delete</span> <i class="fas fa-trash"></i>
                                                </a> -->
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Available Data</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
@endsection

@section('page-js')
@include('admin.modals.pagebuilder.index')
<script src="{{ url('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ url('./library/js/admin/pagebuilder/jsactions.js') }}"></script>
<!-- {!! JsValidator::formRequest('App\Http\Requests\PageRequest') !!} -->
@endsection