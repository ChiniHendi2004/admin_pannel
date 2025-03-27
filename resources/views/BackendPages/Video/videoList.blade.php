@extends('layouts.app')

@section('pagetitle')
Video || List
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header ">
        <div class="container-fluid">
            <div class="row mb-2 ">
                <div class="col-sm-6">
                    <h1 class="m-0">Video List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Video List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-3">

        <div class="card  ">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Video-List/Page')}}">
                    <button type="submit" class="btn text-white" style="background-color: #00008B"><i class="fas fa-plus-circle mr-2"></i>Add new </button>
                </a>
            </div>
        </div>
        <div id="responseMessage"></div>
        <div class="row" id="cardContainer">

        </div>

    </div>
</div>


@section('scripts')
<script>
    function videolist() {

        var video_list = ""
        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            url: `/Video/List`,
            success: function(response) {
                console.log(response);
                var nodata_card = $(`<div class="card text-center mx-4" style="width:100%; height:455px; background-color: #00008B;">
            <h1 style="padding:15%;" class="d-flex justify-content-center text-white" >Create New Video </h1>
          </div>`)

                if (response.data.length === 0) {
                    cardContainer.append(nodata_card)
                }
                for (let i = 0; i < response.data.length; i++) {
                    video_list = response.data[i];
                    var video_title = video_list.video_name
                    var dynamicImageUrl = response.data[i].video_thumbnail
                    var nodata_card = $(`<div class="card text-center" style="width:100%; height:455px; background-color: #00008B;">
              <h1 style="padding-top:15%;" class="d-flex justify-content-center text-white" >Create New Video </h1>
            </div>`)
                    if (response.data.length === 0) {
                        cardContainer.append(nodata_card)
                    }

                    var card = `
           <div class="col-lg-4 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <img src="${dynamicImageUrl}" class="rounded "  style="border:none; background-color: transparent ; box-shadow: none ; width: 100% ">

                    <span class="d-flex justify-content-center">
                      <a href="{{ url('/Video-Content/${video_list.id}') }}">
                        <h4 class="font-weight-bold my-2">${video_title}</h4>
                      </a>
                    </span>
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{ url('/Edit-Video/${video_list.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
                      <a class="dropdown-item" href="{{ url('/Video-Content/${video_list.id}') }}"><i class="far fa-eye mr-2" style="color: #047c32;"></i>view</a>
                      <a class="dropdown-item deletebtn" href="#" data-video-id="${video_list.id}"><i class="fas fa-trash-alt mr-2" style="color: #e01f45;"></i>Delete</a>
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

    $(document).ready(function() {
        videolist();
    });

    $(document).on('click', '.deletebtn', function() {
        var video_id = $(this).data('video-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            url: `/Video/Remove/${video_id}`,
            dataType: "json",
            success: function(response) {

                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                albumlist()
            }
        });
    });
</script>



@endsection




@endsection