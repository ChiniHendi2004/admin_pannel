@extends('layouts.app')

@section('pagetitle')
Content-Group || Create
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0" style="color: black;">Create Group</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create Group</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">

        <div id="responseMessage"></div>

        <div class="row px-2 mt-3">
            <div class="col-lg-6">
                <div class="card p-3">
                    <form method="POST" id="myForm">
                        @csrf
                        <div>
                            <div class="mb-3">
                                <label for="content_name" class="form-label">Content Name</label>
                                <input type="text" class="form-control" id="content_name" placeholder="Enter Content" name="group_name">
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 25rem;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body table-responsive p-3" style="height: 338px">
                        <table class="table table-bordered table-hover text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/Content-Group/Edit') }}" method="POST" id="UpdateContentForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title modal-title-update">Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="">
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" id="group_name" placeholder="Enter Facility Name" name="group_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" style="background-color: #00008B">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="myModaldel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete?</h5>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function clearTable() {
        $('#data-table tbody').empty();
    }

    function fetchDataAndPopulateTable() {
        clearTable();
        $.ajax({
            url: '/Content-Group/List/Latest',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let counter = 1;
                $.each(response.data, function(index, item) {
                    let statusSelect =
                        `<select class="form-select select-status" data-id="${item.id}">
                            <option value="1" ${item.status == 1 ? 'selected' : ''}>Active</option>
                            <option value="0" ${item.status == 0 ? 'selected' : ''}>Inactive</option>
                        </select>`;
                    let editButton =
                        `<button class="btn btn-primary btn-sm editbtn ms-4" data-content-id="${item.id}"><i class="fas fa-edit"></i></button>`;
                    let deleteButton =
                        `<button class="btn btn-danger btn-sm deletebtn" data-content-id="${item.id}"><i class="fas fa-trash-alt"></i></button>`;
                    let row = `<tr><td>${counter}</td><td>${item.group_name}</td><td>${statusSelect}</td><td>${editButton} ${deleteButton}</td></tr>`;
                    $('#data-table tbody').append(row);
                    counter++;
                });
            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }

    $(document).ready(function() {
        $('#myForm').submit(function(e) {
            e.preventDefault();
            let csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token from meta tag

            $.ajax({
                type: 'POST',
                url: '/Content-Group/Add',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    $('#myForm')[0].reset();
                    fetchDataAndPopulateTable();
                },
                error: function() {
                    $('#responseMessage').html('<div class="alert alert-danger">Error submitting the form</div>');
                }
            });
        });


        fetchDataAndPopulateTable();

        $(document).on('click', '.editbtn', function() {
            let id = $(this).data('content-id');
            $('#id').val(id);
            $('#editModal').modal('show');
            $.ajax({
                type: 'GET',
                url: `/Content-Group/Select/${id}`,
                success: function(response) {
                    $('#group_name').val(response.data.group_name);
                    $('.modal-title-update').text(`Update ${response.data.group_name}`);
                }
            });
        });

        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('content-id');
            $('#id').val(id);
            $('#myModaldel').modal('show');

            $.ajax({
                type: "GET",
                //   url: "/Updates-Master/Select/" + id,
                url: `/Content-Group/Select/${id}`,
                success: function(response) {
                    $('#group_name').val(response.data.group_name);
                    $('.modal-title').html(`${response.data.group_name}`)

                },
            });
        });

        $('#myModaldel').on('click', '#delete-confirm-button', function() {
            let id = $('#id').val();
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'DELETE',
                url: `/Content-Group/Remove/${id}`,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#myModaldel').modal('hide');
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    fetchDataAndPopulateTable();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#UpdateContentForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'Post',
                url: `/Content-Group/Edit`,
                data: $(this).serialize(),
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    $('#editModal').modal('hide');
                    fetchDataAndPopulateTable();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });



        $('#data-table').on('change', '.select-status', function() {
            let selectElement = $(this);
            let contentId = selectElement.data('id');
            let selectedValue = selectElement.val();

            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'PATCH',
                url: `/Content-Group/EditStatus/${contentId}`,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    status: selectedValue
                },
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                }
            });
        });
    });
</script>
@endsection
@endsection