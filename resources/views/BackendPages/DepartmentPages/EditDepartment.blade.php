@extends('layouts.app')

@section('pagetitle')
Department || Edit
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) --> 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h3 class="mb-2" style="color: black;">Edit Department</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Department</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-2">
        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card p-3">
                    <form action="" id="form">
                        @csrf
                        <input type="hidden" id="department_id" value="{{ $id }}">

                        <div class="mb-3">
                            <label for="department_name" class="form-label">Class</label>
                            <input type="text" class="form-control" id="department_name" placeholder="Enter Class Name" name="department_name" value="{{ old('department_name') }}">
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Department Text</label>
                            <textarea class="form-control" id="short_description" name="short_description" placeholder="Enter About Department" aria-placeholder="Write Something Here" required>{{ old('short_description') }}</textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 10rem;">Submit</button>
                            <div id="addmore"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function getinfo() {
        var department_id = $('#department_id').val();

        $.ajax({
            type: "GET",
            url: `/Department/Get/title/${department_id}`,
            dataType: "json",
            success: function(response) {
                $('#department_name').val(response.data.department_name);
                $('#short_description').val(response.data.short_description);
                $('#addmore').html(`
                    <a href="{{ url('/Department-Details/Page/${department_id}') }}"> 
                        <button type="button" class="btn btn-secondary text-white ms-3" style="width: 10rem; color:grey;">Add More</button>
                    </a>
                `);
            }
        });
    }

    $(document).ready(function() {
        getinfo();
    });

    $(document).ready(function() {
        var department_id = $('#department_id').val();
        $('form').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: `/Edit-Department/title/${department_id}`,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    getinfo();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
@endsection