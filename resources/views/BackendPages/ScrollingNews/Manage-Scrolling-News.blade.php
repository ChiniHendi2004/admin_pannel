@extends('layouts.app')
@section('pagetitle')
News || Create
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h3 class="m-0" style="color: black;">Create News</h3>
        </div><!-- /.col -->
        <div class="col-sm-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb float-end">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create News</li>
            </ol>
          </nav>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div id="responseMessage"></div>
    <div class="row px-2">
      <div class="col-lg-10">
        <div class="card px-3 py-3">
          <form action="" id="myForm">
            @csrf

            <!-- Academic Session Dropdown (Optional) -->
            <!-- Uncomment if needed -->
            <!-- <div class="mb-3">
              <label for="AcademicDropdown" class="form-label">Academic Session</label>
              <select class="form-select" name="academic_session" id="AcademicDropdown">
                <option value="#" selected disabled>Select Session</option>
                <option value="{{ old('2024-2025') }}">2024-2025</option>
                <option value="{{ old('2025-2026') }}">2025-2026</option>
                <option value="{{ old('2026-2027') }}">2026-2027</option>
              </select>
            </div> -->

            <div class="mb-3">
              <label for="heading" class="form-label">Heading</label>
              <input type="text" class="form-control" id="heading" placeholder="Enter News Title" name="heading" value="{{ old('heading') }}">
              <span class="text-danger"></span>
            </div>

            <div class="row">
              <div class="col-lg-6 mb-3">
                <label for="publication_date" class="form-label">Publication Date</label>
                <input type="date" class="form-control" id="publication_date" name="publication_date" value="{{ old('publication_date') }}">
                <span class="text-danger"></span>
              </div>
              <div class="col-lg-6 mb-3">
                <label for="heading_url" class="form-label">Heading URL</label>
                <input type="url" class="form-control" id="heading_url" name="heading_url" placeholder="Enter URL" value="{{ old('heading_url') }}">
                <span class="text-danger"></span>
              </div>
            </div>

            <div class="mb-3">
              <label for="body" class="form-label">Message</label>
              <textarea class="form-control" id="body" name="body" placeholder="Enter Message" required>{{ old('body') }}</textarea>
              <small id="error-roleDesc" class="text-danger"></small>
            </div>

            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 20rem;">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script>
  // Date modification
  function formatDate(dateString) {
    const options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, options);
  }

  // Clear Table
  function clearTable() {
    $('#data-table tbody').empty();
  }

  // On page load
  $(document).ready(function() {
    fetchDataAndPopulateTable();
  });

  // Form Submit
  $(document).ready(function() {
    $('#myForm').submit(function(e) {
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: `/Scrooling-News/Add`,
        data: $('#myForm').serialize(),
        dataType: "json",
        success: function(response) {
          $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
          $('#myForm')[0].reset();
          fetchDataAndPopulateTable();
        },
        error: function(error) {
          $('#responseMessage').html('<div class="alert alert-danger">Error submitting the form</div>');
        }
      });
    });
  });
</script>
@endsection
@endsection