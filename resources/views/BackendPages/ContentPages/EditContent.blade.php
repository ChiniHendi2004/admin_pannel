@extends('layouts.app')

@section('pagetitle')
Content || Edit
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h3 class="mb-2" style="color: black;">Edit Content</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Content</li>
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
                        <input type="hidden" id="content_id" value="{{ $id }}">

                        <div class="mb-3">
                            <label for="title" class="form-label">Content Name</label>
                            <input type="text" class="form-control" id="title" placeholder="Enter Facility Name" name="title" value="{{ old('title') }}">
                        </div>

                        <!-- <div class="mb-2 col-lg-12">
                            <label for="facility_image" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="facility_image" placeholder="Enter Image URL" name="facility_image" required>
                            <small id="error-facility_image" class="text-danger"></small>
                        </div>

                        <div class="mb-2 col-lg-12">
                            <img id="imagePreview" src="" alt="Image Preview" style="max-width: 100%; height: auto; display: none; border: 1px solid #ddd; padding: 5px;">
                        </div> -->

                        <div class="mb-3">
                            <label for="short_description" class="form-label">About Content</label>
                            <textarea class="form-control" id="short_description" name="short_description" placeholder="Enter About Department" aria-placeholder="Write Something Here" required>{{ old('short_description') }}</textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 10rem;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // $('#facility_image').on('input', function () {
    //     const imageUrl = $(this).val();
    //     if (imageUrl) {
    //         $('#imagePreview').attr('src', imageUrl).show();
    //     } else {
    //         $('#imagePreview').hide();
    //     }
    // });


    function getinfo() {
        var content_id = $('#content_id').val();

        $.ajax({
            type: "GET",
            url: `/Content/Get/Title/${content_id}`,
            dataType: "json",
            success: function(response) {
                let list = response.data;

                $('#title').val(list.title);
                $('#short_description').val(list.short_description);

            }
        });
    }

    $(document).ready(function() {
        getinfo();
    });

    $(document).ready(function() {
        var content_id = $('#content_id').val();
        $('form').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: `/Edit-Content/${content_id}`,
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