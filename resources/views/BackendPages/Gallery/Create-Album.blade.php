@extends('layouts.app')

@section('pagetitle')
Gallery || Create Gallery
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Create Gallery</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Create Gallery</li>
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
            <form id="album_form" enctype="multipart/form-data">
              @csrf

              <div class="card-body p-3">
                <div>
                  <div class="mb-3">
                    <label for="sds" class="form-label">Album Name</label>
                    <input type="text" class="form-control" id="sds" placeholder="Enter Album Type Name" name="album_name" value="{{ old('album_name') }}">
                  </div>
                </div>

                <div>
                  <div class="mb-3">
                    <label for="customUrl" class="form-label">Image Thumbnail URL</label>
                    <input class="form-control" type="text" id="customUrl" name="thumbnail_url" placeholder="Enter Thumbnail URL" value="{{ old('thumbnail_url') }}">
                    <div id="imagePreview" class="mt-2"></div>
                  </div>
                </div>

                <div class="">
                  <button type="submit" class="btn text-white submitalbum" style="background-color: #00008B">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>


      </div>
      <div class="col-lg-8 ">
        <div class="card align-middle">
          <p class="d-flex justify-content-center font-weight-bold pt-2">Gallery Section</p>
        </div>
        <div class="row table-head-fixed" id="cardContainer" style=" height:550px ; overflow: overlay;">


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
  // Get Dropdown List Data 
  $(document).ready(function() {
    albumlist()
  });


  function albumlist() {

    var album_list = ""
    var cardContainer = $('#cardContainer');
    cardContainer.empty();
    $.ajax({
      type: "GET",
      // url: "/Album/List",
      url: `/Album/List`,
      success: function(response) {
        var nodata_card = $(`<div class="card text-center mx-3" style="width:100%; height:455px; background-color: Gray;">
              <h1 style="padding-top:20%;" class="d-flex justify-content-center text-white" >Create New Album </h1>
            </div>`)

        if (response.data.length === 0) {
          cardContainer.append(nodata_card)
        }
        for (let i = 0; i < response.data.length; i++) {
          album_list = response.data[i];
          var album_title = album_list.name
          var dynamicImageUrl = album_list.thumbnail_url
          var card = `
             <div class="col-lg-6 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <img src="${dynamicImageUrl}" class="rounded "  style="border:none; background-color: transparent ; box-shadow: none ; width: 100%; height:200px ">

                    <span class="d-flex justify-content-center">
                      <a href="{{ url('/Album-Content/${album_list.id}') }}">
                        <h4 class="font-weight-bold my-2" id="album_title">${album_title}</h4>
                      </a>
                    </span>
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{ url('/Edit-Album/${album_list.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
                      <a class="dropdown-item" href="{{ url('/Album-Content/${album_list.id}') }}"><i class="far fa-eye mr-2" style="color: #047c32;"></i>view</a>
                      <a class="dropdown-item deletebtn" href="#" data-album-id="${album_list.id}"><i class="fas fa-trash-alt mr-2" style="color: #e01f45;"></i>Delete</a>
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





  // Album Insert 

  $(document).ready(function() {
    $("#album_form").submit(function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      var form = this;
      $.ajax({
        type: "POST",
        url: `/Create-Album/Add`,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          form.reset();
          $('#responseMessage').html('<div class="alert alert-success">' + response.message +
            '</div>');
          albumlist()

        },
        error: function(error) {

          console.error(error);
        },
      });
    });
  });

  // Album Delete
  $(document).on('click', '.deletebtn', function() {
    var album_id = $(this).data('album-id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type: "DELETE",
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      // url: "/Album/Remove/"+ album_id,
      url: `/Album/Remove/${album_id}`,
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