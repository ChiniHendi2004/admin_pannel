@extends('layouts.app')
@section('pagetitle')
Update || Edit
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Edit Updates</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit Updates</li>
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
      <div class="col-lg-6">
        <div class="card  p-3">
          <form action="" id="update-form">
            @csrf
            <input type="hidden" id="title_id" value="{{ $id }}">
            <div class="form-group my-2">
              <label for="title">Title</label>
              <div class="input-container">
                <input type="text" class="form-control" id="title" placeholder="Enter Title" name="title" value="{{ old('title') }}">
              </div>
            </div>
            <div class="form-group my-2">
              <label for="date">Date</label>
              <div class="input-container">
                <input type="date" class="form-control" id="date" name="updates_date" value="{{ old('updates_date') }}">
              </div>
            </div>
            <div class="form-group my-2">
              <label for="update_img">Update Image</label>
              <div class="input-container">
                <input type="text" class="form-control" id="update_img" placeholder="Enter display Image" name="display_image" value="{{ old('display_image') }}">
              </div>
            </div>
            <!-- <div class="form-group">
              <label for="">Select Image</label>
              <div class="input-group mb-3">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="customFile" name="updates_img" onchange="updateLabel(); previewImage();" accept="image/*">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
              </div>
              <div id="imagePreview">
              </div>
            </div> -->
            <div class="d-flex">
              <button type="submit" class="btn text-white mr-2" style="background-color: #00008B">Submit</button>
              <div id="addmore">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="px-3">
          <div class="card p-2" id="img_data">
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
  function getinfo() {
    var title_id = $('#title_id').val();
    $('#img_data').empty();
    $.ajax({
      type: "GET",
      url: `{{ url('/Updates/Select/${title_id}') }}`,
      dataType: "json",
      success: function(response) {
        var dynamicImageUrl = `${response.updates.display_image}`
        console.log(dynamicImageUrl);
        $('#img_data').html(`<img src="${dynamicImageUrl}"   style="height: 314px"  class="img-fluid " alt="">`)
        $('#title').val(response.updates.title)
        $('#date').val(response.updates.event_date)
        $('#update_img').val(response.updates.display_image)
        $('#addmore').html(`
         <a href="{{ url('/Updates-Content/${title_id}') }}"> 
         <button type="button" class="btn text-white" style="background-color: #8181af">Add More</button>
         </a>
          `)
      }
    });
  }
  $(document).ready(function() {
    getinfo()
  });
  $(document).ready(function() {
    var title_id = $('#title_id').val();

    $('#update-form').submit(function(event) {
      event.preventDefault();

      var formData = new FormData(this); // Create FormData object

      // Ensure CSRF token is included
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

      $.ajax({
        type: "POST", // Use POST if Laravel doesn't allow PUT in FormData
        url: `{{ url('/Edit/Updates/') }}/${title_id}`, // Fix dynamic URL
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
          $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
          $("#imagePreview").empty();
          $(".custom-file-label").text('Choose file');
          getinfo();
        },
        error: function(xhr, status, error) {
          console.error("Error:", xhr.responseText);
        }
      });
    });
  });
</script>
@endsection
@endsection