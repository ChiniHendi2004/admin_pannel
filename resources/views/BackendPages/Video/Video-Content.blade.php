@extends('layouts.app')

@section('pagetitle')
Video || Video Content
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Video Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Video Contents</li>
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
                        <form enctype="multipart/form-data" method="POST" id="video_contant">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" id="id" value="{{ $id }}">
                                <div id="thumbnail">

                                </div>
                                <div>
                                    <p style="background: #00008B;color: #fff; padding: 2px;border-radius: 2px;">Add Video Content</p>
                                    <div class="mb-3">
                                        <label for="video_link" class="form-label">Video Link</label>
                                        <input class="form-control" id="video_link" name="video_link" placeholder="Enter Video Link" required value="{{old('video_link') }}">
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="video-heading"></p>
                </div>
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="d-flex justify-content-center my-2" id="video_img">
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
    // master video get 
    function videodetails() {
        var id = $('#id').val();
        var video_title = $('#video-heading');
        var video_img = $('#video_img');
        video_img.empty();
        $.ajax({
            type: "GET",
            // url: "/video/get/" + id,
            url: `/Video/get/${id}`,
            dataType: "json",
            success: function(response) {
                var dynamicImageUrl = response.data.video_thumbnail
                $('#video_name').val(response.data.video_name)
                $('#thumbnail').html(`<input type="hidden" id="thumbnail_url" value=${response.data.video_thumbnail} name="thumbnail_url">`)
                $('#video-heading').html(response.data.video_name)
                video_img.html(
                    `<img src="${dynamicImageUrl}" alt=""  class="img-thumbnail" style="width: 100%; height:280px;" >`
                )

            }
        });
    }

    $(document).ready(function() {
        videodetails()
    });

    //  video content select list wise 

    function videoContentlist() {
        var id = $('#id').val();
        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            // url: "/video-Content/List/" + id,
            url: `/Video-Content/List/${id}`,
            dataType: "json",
            success: function(response) {
                for (let i = 0; i < response.data.length; i++) {
                    const element = response.data[i];
                    var dynamicImageUrl = response.data[i].video_link
                    var card = ` <div class="col-lg-6 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <iframe width="100%" height="220" src="${dynamicImageUrl}"></iframe>
                    
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{url('/Video/Edit-Content/get/${element.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
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
        videoContentlist()
        videodetails()
    });



    // Form Submit video content
    $('form').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        var id = $('#id').val();
        $.ajax({
            type: "POST",
            url: `/Video-Content/Add/${id}`,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",


            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                $("#imagePreview").empty();
                videoContentlist()
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
            // url: "/video-Content/Remove/"+ content_id,
            url: `/Video-Content/Remove/${content_id}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                videoContentlist()

            }
        });

    })
</script>
@endsection
@endsection