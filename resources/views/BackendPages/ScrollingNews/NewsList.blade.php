@extends('layouts.app')

@section('pagetitle')
News || List
@endsection

@section('styles')
<link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-10">
          <h3 class="m-0" style="color: black;">News List</h3>
        </div>
        <div class="col-sm-2">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">News List</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div id="responseMessage"></div>
  <div class="content px-2">
    <div class=" card ">
      <div class="my-2 mx-4 d-flex justify-content-end">
        <a href="{{ url('/Create-News') }}">
          <button type="submit" class="btn text-white" style="background-color: #00008B"><i
              class="fas fa-plus-circle pe-2"></i>Add new</button>
        </a>
      </div>
    </div>
    <div>
      <div class="card">
        <div class="card-body table-responsive p-2" style="height: 65vh;">
          <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="news-table">
            <thead>
              <tr>
                <th style="width: 6.50782%;">ID</th>
                <th style="width: 80rem;">Heading</th>
                <th>Heading URL</th>
                <th style="width: 20rem;">Status</th>
                <th class="pe-1">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this news item?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
  function fetchNewsList() {
    if ($.fn.DataTable.isDataTable('#news-table')) {
      $('#news-table').DataTable().destroy();
      $('#news-table tbody').empty();
    }

    $.ajax({
      type: "GET",
      url: `/Scrooling-News/List`,
      dataType: "json",
      success: function(response) {
        var list = response.data;
        var counter = 1;

        $.each(list, function(index, item) {
          var headingUrl = `<a href="${item.heading_url}" target="_blank">${item.heading_url}</a>`;
          var statusSelect = `<select class="form-control">
                        <option value="active" ${item.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="inactive" ${item.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                    </select>`;
          var viewContent = `<a href="/View-News/Detail/${item.id}" class="btn py-2 mr-2 rounded mx-1" style="background-color: #16641b; font-size: 13px;"><i class="far fa-eye text-white"></i></a>`;
          var editButton = `<a href="/Manage-News/Update/Page/${item.id}" class="btn py-2 mr-2 rounded editbtn text-white" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit text-white"></i></a>`;
          var deleteButton = `<button class="btn py-2 rounded deletebtn text-white" data-id="${item.id}" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt text-white"></i></button>`;

          var row = `<tr>
                        <td>${counter}</td>
                        <td>${item.heading}</td>
                        <td>${headingUrl}</td>
                        <td>${statusSelect}</td>
                        <td>${viewContent} ${editButton} ${deleteButton}</td>
                    </tr>`;

          $('#news-table tbody').append(row);
          counter++;
        });

        $('#news-table').DataTable({
          deferRender: true,
          processing: true,
          ordering: true,
          searching: true,
        });
      }
    });
  }

  $(document).ready(function() {
    fetchNewsList();
  });

  $(document).on('click', '.deletebtn', function() {
    var newsId = $(this).data('id');
    $('#confirmDeleteModal').modal('show');
    $('#confirmDelete').data('id', newsId);
  });

  // Confirm delete action
  $('#confirmDelete').click(function() {
    var id = $(this).data('id');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type: "DELETE",
      url: `/Scrooling-News/Remove/${id}`,
      data: {
        _method: 'DELETE'
      },
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      success: function(response) {
        $('#confirmDeleteModal').modal('hide');
        $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
        fetchNewsList();
      }
    });
  });
</script>
@endsection
@endsection