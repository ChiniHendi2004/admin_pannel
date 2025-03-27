@extends('layouts.app')

@section('pagetitle')
Gallery || Gallery Content
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Gallery Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Album Gallery</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-3">

        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-4  ">
                <div class="">

                    <div class="card pt-3">

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form enctype="multipart/form-data" method="POST" id="album_contant">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" id="album_id" value="{{ $id }}">
                                <div id="thumbnail">

                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label for="image_url" class="form-label">Album Image</label>
                                        <input class="form-control" id="image_url" name="image_url" placeholder="Enter Album Image Url" required value="{{old('image_url') }}">
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">About Album Image</label>
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="album-heading"></p>
                </div>
                <div class="row">

                    <div class="col-lg-12 ">

                        <div class="d-flex justify-content-center my-2" id="album_img">
                        </div>
                    </div>

                    <div class="row" id="cardContainer" style=" height: 35rem; overflow: overlay;"></div>


                </div>
            </div>
        </div>

    </div>
</div>


@section('scripts')
<script>
    // function updateLabel() {
    //     const fileInput = document.getElementById('customFile');
    //     const label = document.querySelector('.custom-file-label');

    //     if (fileInput.files.length > 0) {
    //         label.textContent = fileInput.files[0].name;
    //     } else {
    //         label.textContent = 'Choose file';
    //     }
    // }

    function previewImage() {
        const imagePreview = document.getElementById('imagePreview');

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.style.maxWidth = '100%';
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
    // master album get 
    function albumdetails() {
        var id = $('#album_id').val();
        var album_title = $('#album-heading');
        var album_img = $('#album_img');
        album_img.empty();
        $.ajax({
            type: "GET",
            // url: "/Album/get/" + id,
            url: `/Album/get/${id}`,
            dataType: "json",
            success: function(response) {
                var dynamicImageUrl = response.data.thumbnail_url
                $('#album_name').val(response.data.name)
                $('#thumbnail').html(`<input type="hidden" id="thumbnail_url" value=${response.data.thumbnail_url} name="thumbnail_url">`)
                $('#album-heading').html(response.data.name)
                album_img.html(
                    `<img src="${dynamicImageUrl}" alt=""  class="img-thumbnail" style="width: 500px; height:280px;" >`
                )

            }
        });
    }

    $(document).ready(function() {
        albumdetails()
    });

    //  album content select list wise 

    function albumContentlist() {
        var id = $('#album_id').val();
        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            // url: "/Album-Content/List/" + id,
            url: `/Album-Content/List/${id}`,
            dataType: "json",
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const element = response.data[i];
                    var dynamicImageUrl = response.data[i].image_url
                    var card = ` <div class="col-lg-4 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <img src="${dynamicImageUrl}" class="rounded "  style="border:none; background-color: transparent ; box-shadow: none ; width: 100% ">

                    
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{url('/Video/Content/get/${element.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
                      <a class="dropdown-item deletebtn" data-delete-content="${element.id}" href="#"><i class="fas fa-trash-alt mr-2" style="color: #e01f45;"></i>Delete</a>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>`;
                    cardContainer.append(card);
                }
            }
        });
    }


    $(document).ready(function() {
        albumContentlist()
        albumdetails()
    });



    // Form Submit album content
    $('form').submit(function(event) {
        event.preventDefault();
        var thumb = $('#thumbnail_url').val();
        var formData = new FormData(this);

        var id = $('#album_id').val();
        $.ajax({
            type: "POST",
            url: `/Album-Content/Add/${id}`,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",


            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                $("#imagePreview").empty();
                albumContentlist()
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.deletebtn', function() {
        var content_id = $(this).data('delete-content');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "DELETE",
            url: `/Video-Content/Remove/${content_id}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                albumContentlist()

            }
        });

    })
</script>
@endsection
@endsection