@extends('layouts.app')

@section('pagetitle')
Video || Edit Video
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Edit Video</h1>
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


                        <form id="updatevideo" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body pt-3">
                                <input type="hidden" id="id" value="{{ $id }}">

                                <div>
                                    <div class="mb-3">
                                        <label for="video_name" class="form-label">Video Name</label>
                                        <input type="text" class="form-control" id="video_name" placeholder="Enter Video Name" name="video_name" value="{{ old('video_name') }}">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="video_thumbnail" class="form-label">Video Thumbnail URL</label>
                                        <input type="text" class="form-control" id="video_thumbnail" placeholder="Enter video thumbnail" name="video_thumbnail" value="{{ old('video_thumbnail') }}">
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2" id="video-heading">video Section</p>
                </div>
                <div class="d-flex justify-content-start" id="video_img">

                </div>
            </div>
        </div>

    </div>
</div>



@section('scripts')

<script>
    function videodetails() {
        var id = $('#id').val();
        var video_name = $('#video_name');
        var video_thumbnail = $('#video_thumbnail');
        var video_img = $('#video_img');
        video_img.empty();

        $.ajax({
            type: "GET",
            // url: "/video/get/" + id,
            url: `/Video/get/${id}`,
            dataType: "json",
            success: function(response) {
                var dynamicImageUrl = response.data.video_thumbnail
                video_name.val(response.data.video_name)
                video_thumbnail.val(response.data.video_thumbnail)
                video_img.html(
                    `<img src="${dynamicImageUrl}" alt=""  class="img-thumbnail" style="width: 100%; height:250px;" >`
                )
            }
        });
    }


    $(document).ready(function() {
        videodetails()
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
                // url: "/video/update/" + id,
                url: `/Video/update/${id}`,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');

                    $("#imagePreview").empty();
                    $(".custom-file-label").text('Choose file');
                    videodetails()
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