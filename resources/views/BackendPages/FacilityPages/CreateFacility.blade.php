@extends('layouts.app')
@section('pagetitle')
Facility || Create
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-10">
                    <h3 class="m-0">Create Facility</h3>
                </div>
                <div class="col-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Facility</li>
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
                                    <label for="updatesDropdown" class="form-label">Department Type</label>
                                    <select class="form-control" name="id" id="updatesDropdown">
                                        <option value="" selected disabled>Select Department</option>
                                    </select>
                                </div>

                                <div class="mb-2 col-lg-6">
                                    <label for="facility_name" class="form-label">Facility</label>
                                    <input type="text" class="form-control" id="facility_name" placeholder="Enter Class Name" name="facility_name">
                                </div>

                                <div class="mb-2 col-lg-12">
                                    <label for="facility_image" class="form-label">Image URL</label>
                                    <input type="url" class="form-control" id="facility_image" placeholder="Enter Image URL" name="facility_image" required>
                                    <small id="error-facility_image" class="text-danger"></small>
                                </div>

                                <!-- Image Preview -->
                                <div class="mb-2 col-lg-12">
                                    <img id="imagePreview" src="" alt="Image Preview" style="max-width: 100%; height: auto; display: none; border: 1px solid #ddd; padding: 5px;">
                                </div>


                                <div class="mb-3 col-lg-12">
                                    <label for="short_description" class="form-label">About Facility</label>
                                    <textarea class="form-control" id="short_description" name="short_description" placeholder="Enter About Department" aria-placeholder="Write Something Here" required></textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>

                                <div class="mb-3 col-lg-12">
                                    <div id="summernote" name="paragraph_text"></div>
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
    $('#facility_image').on('input', function() {
        const imageUrl = $(this).val();

        if (imageUrl) {
            $('#imagePreview')
                .attr('src', imageUrl)
                .on('error', function() {
                    $(this).hide();
                    alert('Invalid image URL. Please enter a valid URL.');
                })
                .show();
        } else {
            $('#imagePreview').hide();
        }
    });


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
        Dropdownlist = $('#updatesDropdown')
        $.ajax({
            type: "GET",
            url: `/Facility-Group/StatuswiseList`,
            dataType: "json",
            success: function(response) {
                console.log(response);

                list = response.data;
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
        var summernoteValue = $('#summernote').summernote('code');
        formData.append('paragraph_text',summernoteValue)
        // Perform the AJAX request
        $.ajax({
            type: "POST",
            url: `/Create-Facility/Add`,
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