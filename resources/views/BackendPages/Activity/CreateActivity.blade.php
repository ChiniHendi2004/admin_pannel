@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 fw-bold">Create Activity</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Activity</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <div class="content px-4">
        <div class="row">
            <div class="col-lg-8">
                <form id="activityForm">
                    @csrf
                    <div id="responseMessage"></div>
                    <div class="card p-4">
                        <div class="mb-3">
                            <label for="activityGroupDropdown" class="form-label">Activity Group</label>
                            <select class="form-select" name="activity_group_id" id="activityGroupDropdown">
                                <option value="" selected disabled>Select Activity Group</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="activity_name" class="form-label">Title</label>
                            <input type="text" class="form-control" placeholder="Enter Activity Name" name="activity_name" id="activity_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" name="event_date" id="event_date" required>
                        </div>


                        <div class="mb-3 col-lg-12">
                            <label for="short_description" class="form-label">About Activity</label>
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
        Dropdownlist = $('#activityGroupDropdown')
        $.ajax({
            type: "GET",
            url: `/Activity/Group/Active/Status/List`,
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
    $('#activityForm').on('submit', function(e) {
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
            url: `/Create/Activity`,
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