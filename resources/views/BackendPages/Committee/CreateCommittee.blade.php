@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 fw-bold">Create Committee</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Committee</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <div class="content px-4">
        <div class="row">
            <div class="col-lg-8">
                <form id="committeeForm">
                    @csrf
                    <div id="responseMessage"></div>
                    <div class="card p-4">
                        <div class="mb-3">
                            <label for="committeeGroupDropdown" class="form-label">Committee Group</label>
                            <select class="form-select" name="committee_group_id" id="committeeGroupDropdown">
                                <option value="" selected disabled>Select Committee Group</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="committee_name" class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Enter Committee Name" name="committee_name" id="committee_name" required>
                        </div>

            

                        <div class="mb-3 col-lg-12">
                            <label for="short_description" class="form-label">About Committee</label>
                            <textarea class="form-control" id="short_description" name="short_description" placeholder="Enter About Department" aria-placeholder="Write Something Here" required></textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>

                        <div class="mb-3 col-lg-12">
                        <div id="summernote" name="paragraph_text" placeholder="Enter Details" aria-placeholder="Write Something Here" required></div>
                        <small id="error-roleDesc" class="text-danger"></small>

                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
  


    // initilize Summernote COntent editor 
    $(function() {

        // Summernote
        $('#summernote').summernote({
            height: 350, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })


    })


    // get all updates master data in select drop down 

    $(document).ready(function() {
        list = '';
        Dropdownlist = $('#committeeGroupDropdown')
        $.ajax({
            type: "GET",
            url: `/Committee/Group/Active/Status/List`,
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
    $('#committeeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);
        var summernoteValue = $('#summernote').summernote('code');

        formData.append('paragraph_text', summernoteValue);
        formData.append('tenant_id', '1');
        formData.append('client_slug', 'client-part');
        formData.append('status', '1');
        formData.append('website_url', 'www.imh.com');
        formData.append('employee_id', '1');
        formData.append('academic_session', '2025');
        formData.append('expiration_date', '2025-12-12');
        // Perform the AJAX request
        $.ajax({
            type: "POST",
            url: `/Create/Committee`,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {


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