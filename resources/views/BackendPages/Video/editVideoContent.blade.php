@extends('layouts.app')

@section('pagetitle')
Video || Edit Video Content
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Edit Video Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Video Content</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-4">

        <div id="responseMessage"></div>
        <div class="row">

            <div class="col-lg-4  ">
                <div class="">

                    <div class="card ">
                        <form id="updatecontent" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body pt-3">
                                <input type="hidden" id="id" value="{{ $id }}">
                                <div>
                                    <div class="mb-3">
                                        <label for="video_link" class="form-label">Video Link</label>
                                        <input type="text" class="form-control" id="video_link" name="video_link" placeholder="Enter Video Link" required value="{{old('video_link') }}">
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label for="video_desc" class="form-label">Video Description</label>
                                        <textarea class="form-control" id="video_desc" name="video_desc" placeholder="Enter Video Description" required value="{{old('video_desc') }}"></textarea>
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn text-white"
                                        style="background-color: #00008B">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-lg-8 ">
                <div class="card align-middle">
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="video-heading">Video Content</p>
                </div>
                <div class="row">

                    <div class="col-lg-12 ">

                        <div class="d-flex justify-content-center my-2" id="video_content">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('scripts')
<script>
    function updateLabel() {
        const fileInput = document.getElementById('customFile');
        const label = document.querySelector('.custom-file-label');

        if (fileInput.files.length > 0) {
            label.textContent = fileInput.files[0].name;
        } else {
            label.textContent = 'Choose file';
        }
    }

    function previewImage() {
        const fileInput = document.getElementById('customFile');
        const imagePreview = document.getElementById('imagePreview');

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.style.maxWidth = '120%';
                img.style.maxHeight = '200px'; // Adjust the height as needed
                imagePreview.innerHTML = ''; // Clear previous preview
                imagePreview.appendChild(img);
            }

            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = ''; // Clear the preview if no file selected
        }
    }
</script>


<script>
    function getDetails() {
        var content_id = $('#id').val();
        var cardContainer = $('#video_content');
        $.ajax({
            type: "GET",
            // url: "/Album/Content/details/"+ content_id,
            url: `{{ url('/Video/Content/details/${content_id}') }}`,
            dataType: "json",
            success: function(response) {
                var video_link = response.data.video_link
                cardContainer.html(`<iframe width="420" height="345" src="${video_link}"></iframe>`)
                $('#video_desc').val(response.data.video_desc);
                $('#video_link').val(response.data.video_link);
            }
        });
    }

    $(document).ready(function() {
        getDetails();
    });

    $('#updatecontent').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var id = $('#id').val();
        var formData = new FormData(this);
        formData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `{{ url('/Video/Content/Update/${id}') }}`,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');

                getDetails();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        return false; // Add this line to prevent form submission
    });
</script>

@endsection



@endsection