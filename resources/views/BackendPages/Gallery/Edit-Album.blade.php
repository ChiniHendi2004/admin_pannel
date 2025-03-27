@extends('layouts.app')

@section('pagetitle')
Gallery || Edit Gallery
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Edit Gallery</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Starter Page</li>
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


                        <form id="updateAlbum" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body pt-3">
                                <input type="hidden" id="id" value="{{ $id }}">

                                <div>
                                    <div class="mb-3">
                                        <label for="album_name" class="form-label">Album Name</label>
                                        <input type="text" class="form-control" id="album_name" placeholder="Enter Album Type Name" name="album_name" value="{{ old('album_name') }}">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="thumbnail_url" class="form-label">Album Thumbnail URL</label>
                                        <input type="text" class="form-control" id="thumbnail_url" placeholder="Enter thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url') }}">
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="album-heading">Album Section</p>
                </div>
                <div class="d-flex justify-content-start" id="album_img">

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
    function albumdetails() {
        var id = $('#id').val();
        var album_name = $('#album_name');
        var thumbnail_url = $('#thumbnail_url');
        var album_img = $('#album_img');
        album_img.empty();

        $.ajax({
            type: "GET",
            // url: "/Album/get/" + id,
            url: `/Album/get/${id}`,
            dataType: "json",
            success: function(response) {
                var dynamicImageUrl = response.data.thumbnail_url
                album_name.val(response.data.name)
                thumbnail_url.val(response.data.thumbnail_url)
                album_img.html(
                    `<img src="${dynamicImageUrl}" alt=""  class="img-thumbnail" style="width: 500px; height:378px;" >`
                )
            }
        });
    }


    $(document).ready(function() {
        albumdetails()
    });

    $(document).ready(function() {

        var id = $('#id').val();
        $('form').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            formData.append("_method", "PUT");
            console.log(formData);
            
            $.ajax({
                type: "POST",
                // url: "/Album/update/" + id,
                url: `/Album/update/${id}`,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');

                    $("#imagePreview").empty();
                    $(".custom-file-label").text('Choose file');
                    albumdetails()
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