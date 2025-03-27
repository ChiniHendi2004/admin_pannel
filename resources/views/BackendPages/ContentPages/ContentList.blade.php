@extends('layouts.app')

@section('pagetitle')
Content || List
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
                    <h3 class="m-0" style="color: black;">Content List</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Content List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div id="responseMessage"></div>
    <div class="content px-2">
        <div class=" card ">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Create-Content') }}">
                    <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                            class="fas fa-plus-circle pe-2"></i>Add new</button>
                </a>
            </div>
        </div>
        <div>
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body table-responsive p-2" style="height: 65vh;">
                    <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="content-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Content Title</th>
                                <th>Description</th>
                                <th>Add Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contentDeleteModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Delete Content</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h5 class="text-center">This Will Remove All The Content of</h5>
                <h5 class="text-center" id="content-title"></h5>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-content-button" style="background-color: #eb0d1c;">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    function fetchContentList() {
        if ($.fn.DataTable.isDataTable('#content-table')) {
            $('#content-table').DataTable().destroy();
            $('#content-table tbody').empty();
        }

        $.ajax({
            type: "GET",
            url: `/Content/List`,
            dataType: "json",
            success: function(response) {
                var list = response.data;
                var counter = 1;

                $.each(list, function(index, item) {
                    var addDetails = `<a href="/Content-Details/Page/${item.id}" class="btn text-white" style="background-color: #00008B">Add Details</a>`;
                    var viewContent = `<a href="/View-Content/Page/${item.id}" class="btn py-2 mr-2 rounded mx-1" style="background-color: #16641b; font-size: 13px;"><i class="far fa-eye text-white"></i></a>`;
                    var editButton = `<a href="/Edit-Content/${item.id}" class="btn py-2 mr-2 rounded editbtn text-white" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit text-white"></i></a>`;
                    var deleteButton = `<button class="btn py-2 rounded deletebtn text-white" data-id="${item.id}" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt text-white"></i></button>`;

                    var row = `<tr>
                    <td>${counter}</td>
                    <td class="responsive-text">${item.title}</td>
                    <td>${item.short_description}</td>
                    <td>${addDetails}</td>
                    <td>${editButton}${viewContent}${deleteButton}</td>
                </tr>`;

                    $('#content-table tbody').append(row);
                    counter++;
                });

                $('#content-table').DataTable({
                    deferRender: true,
                    processing: true,
                    ordering: true,
                    searching: true,
                });
            }
        });
    }

    // Delete button click
    $(document).on('click', '.deletebtn', function() {
        content_id = $(this).data('id');
        $('#contentDeleteModal').modal('show');

        $.ajax({
            type: "GET",
            url: `/Content/Get/Title/${content_id}`,
            success: function(response) {
                let list = response.data;

                if (list) {
                    $('.modal-title').text(list.title);
                    $('#content-title').text(list.title);
                } else {
                    console.log('No content found');
                }
            }
        });
    });

    // Confirm delete
    $('#contentDeleteModal').on('click', '#delete-content-button', function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "DELETE",
            url: `/Content/remove/${content_id}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#contentDeleteModal').modal('hide');
                $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                fetchContentList(); // Refresh list
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Fetch list on load
    $(document).ready(function() {
        fetchContentList();
    });
</script>
@endsection

@endsection