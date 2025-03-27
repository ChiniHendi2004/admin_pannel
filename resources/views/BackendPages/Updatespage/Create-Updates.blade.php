@extends('layouts.app')
@section('pagetitle')
Update || Create
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h3 class="m-0">Create Update</h3>
        </div><!-- /.col -->
        <div class="col-sm-2">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Create Update</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->



  <!-- Main content -->
  <div class="content px-2">

    <div class="content">
      <div id="responseMessage"></div>
      <div class="row">
        <div class="col-lg-10">
          <form action="POST" id="updatesForm" enctype="multipart/form-data">
            @csrf
            <div class="card p-4">
              <div class="form-group mb-2">
                <label for="updatesDropdown">Updates Type </label>
                <select class="form-control" name="updates_id" id="updatesDropdown">
                  <option value="" selected disabled>Select Update Type</option>
                </select>
              </div>

              <div class="form-group mb-2">
                <label for="Date">Date</label>
                <div class="input-container">
                  <input type="date" class="form-control" id="Date" name="event_date">
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="Title">Title</label>
                <div class="input-container">
                  <input type="text" class="form-control " id="Title" placeholder="Enter Title" name="title">
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="display_image">Image Url</label>
                <div class="input-container">
                  <input type="text" class="form-control " id="display_image" placeholder="Enter image Url" name="display_image">
                  <div id="imagePreview" class="mt-2"></div>
                </div>
              </div>
              <!-- <div class="mb-2">
                <label for="formFile" class="form-label">Select Image</label>
                <input class="form-control" type="file" id="formFile" name="updates_img" accept="image/*">
                <div id="imagePreview" class="mt-2"></div>
              </div> -->

              <div class="mb-3">
                <label for="Content" class="form-label">About Update</label>
                <div class="mb-2 col-lg-12">
                  <textarea class="form-control" id="summernote" name="updates_content" placeholder="Enter Details" aria-placeholder="Write Something Here" required></textarea>
                  <small id="error-roleDesc" class="text-danger"></small>
                </div>
              </div>

              <div>
                <button type="submit" class="btn text-white" style="background-color: #00008B">Submit</button>
              </div>
            </div>
          </form>
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
  // initilize Summernote COntent editor 
  $(function() {

    // Summernote
    $('#summernote').summernote({
      height: 300, // set editor height
      minHeight: null, // set minimum height of editor
      maxHeight: null, // set maximum height of editor
      focus: true,
    })


  })

  // get all updates master data in select drop down 

  $(document).ready(function() {
    list = '';
    Dropdownlist = $('#updatesDropdown')
    $.ajax({
      type: "GET",
      url: `/Department-Group/StatuswiseList`,
      dataType: "json",
      success: function(response) {
        list = response.data
        for (let i = 0; i < response.data.length; i++) {
          list = response.data[i];

          Dropdownlist.append('<option value="' + list.id + '">' + list.group_name + '</option>');
        }
      }
    });
  });


  // form submit
  $('#updatesForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Get form data
    var formData = new FormData(this);
    for (var pair of formData.entries()) {
      console.log(pair[0] + ': ' + pair[1]);
    }

    // Perform the AJAX request
    $.ajax({
      type: "POST",
      url: `/Create/Updates`,
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        $('#updatesForm')[0].reset();
        $(".custom-file-label").text('Choose file');
        $('#imagePreview').html('');

        $('#responseMessage').html('<div class="alert alert-success">' +
          response.message + '</div>');

      },
      error: function(error) {
        console.error(error);
      }
    });
  });
</script>
@endsection




@endsection