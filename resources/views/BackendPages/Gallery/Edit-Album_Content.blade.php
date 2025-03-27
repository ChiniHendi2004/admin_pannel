@extends('layouts.app')

@section('pagetitle')
Gallery || Edit Content
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Edit Gallery Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Gallery Content</li>
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
                                        <label for="image_url" class="form-label">Album Image URL</label>
                                        <input type="text" class="form-control" id="image_url" name="image_url" placeholder="Enter Image url" required value="{{old('image_url') }}">
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">About Album</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter message" required value="{{old('description') }}"></textarea>
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="album-heading">Album Content</p>
                </div>
                <div class="row">

                    <div class="col-lg-12 ">

                        <div class="d-flex justify-content-center my-2" id="content_img">
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
        var cardContainer = $('#content_img');
        $.ajax({
            type: "GET",
            // url: "/Album/Content/details/"+ content_id,
            url: `{{ url('/Album/Content/details/${content_id}') }}`,
            dataType: "json",
            success: function(response) {
                var imgUrl = response.data.image_url
                cardContainer.html(`<img src="${imgUrl}" alt="" class="img-thumbnail" style="width: 500px; height:280px;">`)
                $('#description').val(response.data.description);
                $('#image_url').val(response.data.image_url);
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
            url: `{{ url('/Album/Content/Update/${id}') }}`,
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