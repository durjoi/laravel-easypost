@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2>Manage Categories</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">Settings</li>
                            <li class="breadcrumb-item active">Manage Categories</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools">
                                <a href="javascript:void(0)" id="create-category" class="btn btn-primary btn-sm">Create Category</a>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table-hover table-striped text-nowrap table-sm" id="category-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Category Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables-keytable/css/keyTable.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/summernote/summernote-bs4.min.css') }}">
    
@endsection

@section('page-js')
    @include('admin.modals.settings.categories.modal')
    <script src="{{ url('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ url('library/js/admin/settings/components.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/plugins/DataTables-1.10.12/extensions/Pagination/input.js') }}"></script>
    <script type="text/javascript">
        var categoryTable;
        $(document).ready(function() {
            $('#summernote').summernote();
            categoryTable = $('#category-table').DataTable({
                processing: true,
                serverSide: true,
                "pagingType": "input",
                ajax: {
                    url: "{{ url('api/settings/categories') }}",
                    type:'POST'
                },
                columns: [
                    {
                        width:'5%', searchable: false, orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }, className: "text-center"
                    },
                    { data: 'name', name: 'name', searchable: true, orderable: true, width:'85%' },
                    { data: 'action', name: 'action', searchable: false, orderable: false, width:'10%', className: "text-center"},
                ]
            });

        });

        function updatebrand(id){
            $.ajax({
                type: "GET",
                url: "{{ url('admin/settings/brands') }}/"+id+"/edit",
                dataType: "json",
                success: function (response) {
                    $('#brand-name').val(response.brand.name);
                    $('#brand_id').val(response.brand.id);
                    $('#device_type').val(response.brand.device_type);
                    $('#feature').val(response.brand.feature);
                    if(response.brand.photo){
                        $('#div-image').show();
                        $('#image-file').hide();
                        $('#image-val').val(response.brand.photo_display)
                    }
                    $('#modal-brand').modal();
                }
            });
        }

        function deletebrand(id){
            if(confirm('Are you sure you want to delete this?')){
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('admin/settings/brands') }}/"+id,
                    dataType: "json",
                    success: function (response) {
                        alert('Brand has been deleted!');
                        categoryTable.draw();
                    }
                });
            }
        }
    </script>
@endsection