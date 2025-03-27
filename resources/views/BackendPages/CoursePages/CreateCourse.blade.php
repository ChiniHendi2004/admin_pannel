@extends('layouts.app')
@section('pagetitle')
Course || Create
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-10">
                    <h3 class="m-0">Create Course</h3>
                </div>
                <div class="col-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->



    <!-- Main content -->
    <div class="content px-2">

        <div class="content">
            <div id="responseMessage"></div>
            <div class="row">
                <div class="col-lg-10">
                    <form action="POST" id="updatesForm" enctype="multipart/form-data">
                        @csrf
                        <div class="card p-4">
                            <div class="row">
                                <div class="mb-2 col-lg-6">
                                    <label for="Course_group_id" class="form-label">Course Type</label>
                                    <select class="form-control" name="id" id="Course_group_id">
                                        <option value="" selected disabled>Select Course</option>
                                    </select>
                                </div>

                                <div class="mb-2 col-lg-6">
                                    <label for="title" class="form-label">Course Name</label>
                                    <input type="text" class="form-control" id="title" placeholder="Enter Class Name" name="title">
                                </div>

                                <div class="mb-3 col-lg-12">
                                    <label for="short_description" class="form-label">About Course</label>
                                    <textarea class="form-control" id="short_description" name="short_description" placeholder="Enter About Department" aria-placeholder="Write Something Here" required></textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>

                                <div class="mb-3 col-lg-12">
                                    <textarea class="form-control" id="summernote" name="paragraph_text" placeholder="Enter Details" aria-placeholder="Write Something Here" required></textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn text-white" style="background-color: #00008B">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</div>



@section('scripts')

<script>
    // initilize Summernote COntent editor 
    $(function() {

        // Summernote
        $('#summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })


    })


    // get all updates master data in select drop down 

    $(document).ready(function() {
        list = '';
        Dropdownlist = $('#Course_group_id')
        $.ajax({
            type: "GET",
            url: `/Course-Group/StatuswiseList`,
            dataType: "json",
            success: function(response) {
                list = response.data
                for (let i = 0; i < response.data.length; i++) {
                    list = response.data[i];
                    $('#summernote').summernote('code', response.data.paragraph_text);
                    Dropdownlist.append('<option value="' + list.id + '">' + list.group_name + '</option>');
                }
            }
        });
    });


    // form submit
    $('#updatesForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get form data
        var formData = new FormData(this);
        var summernoteValue = $('#summernote').summernote('code', "");

        // Perform the AJAX request
        $.ajax({
            type: "POST",
            url: `/Create-Course/Add`,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#updatesForm')[0].reset();

                $('#responseMessage').html('<div class="alert alert-success">' +
                    response.message + '</div>');

            },
            error: function(error) {

                console.error(error);
            }
        });
    });
</script>
@endsection




@endsection