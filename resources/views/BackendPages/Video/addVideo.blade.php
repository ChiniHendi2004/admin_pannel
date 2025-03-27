@extends('layouts.app')

@section('pagetitle')
Video || Create Video
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Create Video</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Video</li>
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
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="Video_form" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body p-3">
                                <div>
                                    <div class="mb-3">
                                        <label for="video_type" class="form-label">Video Type</label>
                                        <input type="text" class="form-control" id="video_type" placeholder="Enter Video Type" name="video_type" value="{{ old('video_type') }}">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="video_name" class="form-label">Video Name</label>
                                        <input type="text" class="form-control" id="video_name" placeholder="Enter Video Name" name="video_name" value="{{ old('video_name') }}">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="video_thumbnail" class="form-label">Video Thumbnail URL</label>
                                        <input class="form-control" type="text" id="video_thumbnail" name="video_thumbnail" placeholder="Enter Video thumbnail URL" value="{{ old('video_thumbnail') }}">
                                        <div id="imagePreview" class="mt-2"></div>
                                    </div>
                                </div>

                                <div class="">
                                    <button type="submit" class="btn text-white submitVideo" style="background-color: #00008B">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-lg-8 ">
                <div class="card align-middle">
                    <p class="d-flex justify-content-center font-weight-bold pt-2">Video Section</p>
                </div>
                <div class="row table-head-fixed" id="cardContainer" style=" height:550px ; overflow: overlay;">

                </div>
            </div>
        </div>

    </div>
</div>



@section('scripts')

<script>
    // Get Dropdown List Data 
    $(document).ready(function() {
        Videolist()
    });


    function Videolist() {

        var Video_list = ""
        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            // url: "/Video/List",
            url: `/Video/List`,
            success: function(response) {
                console.log(response);
                
                var nodata_card = $(`<div class="card text-center mx-3" style="width:100%; height:455px; background-color: Gray;">
              <h1 style="padding-top:20%;" class="d-flex justify-content-center text-white" >Create New Video </h1>
            </div>`)

                if (response.data.length === 0) {
                    cardContainer.append(nodata_card)
                }
                for (let i = 0; i < response.data.length; i++) {
                    Video_list = response.data[i];
                    var Video_title = Video_list.video_name
                    var dynamicImageUrl = Video_list.video_thumbnail
                    var card = `
             <div class="col-lg-6 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <img src="${dynamicImageUrl}" class="rounded "  style="border:none; background-color: transparent ; box-shadow: none ; width: 100%; height:200px ">

                    <span class="d-flex justify-content-center">
                      <a href="{{ url('/Video-Content/${Video_list.id}') }}">
                        <h4 class="font-weight-bold my-2" id="Video_title">${Video_title}</h4>
                      </a>
                    </span>
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{ url('/Edit-Video/${Video_list.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
                      <a class="dropdown-item" href="{{ url('/Video-Content/${Video_list.id}') }}"><i class="far fa-eye mr-2" style="color: #047c32;"></i>view</a>
                      <a class="dropdown-item deletebtn" href="#" data-Video-id="${Video_list.id}"><i class="fas fa-trash-alt mr-2" style="color: #e01f45;"></i>Delete</a>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
                        
          `;
                    cardContainer.append(card);

                }
            }
        });
    }





    // Video Insert 

    $(document).ready(function() {
        $("#Video_form").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var form = this;
            $.ajax({
                type: "POST",
                url: `/Create-Video/Add`,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    form.reset();
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');
                    Videolist()

                },
                error: function(error) {

                    console.error(error);
                },
            });
        });
    });

    // Video Delete
    $(document).on('click', '.deletebtn', function() {
        var Video_id = $(this).data('Video-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            // url: "/Video/Remove/"+ Video_id,
            url: `/Video/Remove/${Video_id}`,
            dataType: "json",
            success: function(response) {

                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                Videolist()
            }
        });
    });
</script>


@endsection

@endsection