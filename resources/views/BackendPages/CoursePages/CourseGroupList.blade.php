@extends('layouts.app')

@section('pagetitle')
Course Group || List
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Group List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Content</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">

        <div id="responseMessage"></div>

        <div class="row">
            <div class="col-lg-10">
                <div class="card mx-2">
                    <div class="card-body table-responsive px-4  pt-4">
                        <table class="table table-bordered table-hover text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th style="width: 40rem;">Course</th>
                                    <th style="width: 8rem;">Status</th>
                                    <th style="width: 3rem;">Action</th>
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
            <form action="{{ url('/Course-Group/Edit') }}" method="POST" id="updateDepartmentForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title modal-title-update">Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="Course_id" value="">
                    <div class="mb-3">
                        <label for="group_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="group_name" placeholder="Enter update Type Name" name="group_name">
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
            url: '/Course-Group/List',
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
                        `<button class="btn btn-primary btn-sm editbtn me-2" data-department-id="${item.id}"><i class="fas fa-edit"></i></button>`;
                    let deleteButton =
                        `<button class="btn btn-danger btn-sm deletebtn" data-department-id="${item.id}"><i class="fas fa-trash-alt"></i></button>`;
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


        fetchDataAndPopulateTable();

        $(document).on('click', '.editbtn', function() {
            let Course_id = $(this).data('department-id');
            $('#Course_id').val(Course_id);
            $('#editModal').modal('show');
            $.ajax({
                type: 'GET',
                url: `/Course-Group/Select/${Course_id}`,
                success: function(response) {
                    $('#group_name').val(response.data.group_name);
                    $('.modal-title-update').text(`Update ${response.data.group_name}`);
                }
            });
        });

        $(document).on('click', '.deletebtn', function() {
            var Course_id = $(this).data('department-id');
            $('#Course_id').val(Course_id);
            $('#myModaldel').modal('show');

            $.ajax({
                type: "GET",
                //   url: "/Updates-Master/Select/" + update_id,
                url: `/Content-Group/Select/${Course_id}`,
                success: function(response) {
                    $('#group_name').val(response.data.group_name);
                    $('.modal fade').text(`${response.data.group_name}`);
                },
            });
        });

        $('#myModaldel').on('click', '#delete-confirm-button', function() {
            let Course_id = $('#Course_id').val();
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'DELETE',
                url: `/Content-Group/Remove/${Course_id}`,
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

        $('#updateDepartmentForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'Post',
                url: `/Course-Group/Edit/`,
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
            let updateId = selectElement.data('id');
            let selectedValue = selectElement.val();
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'PATCH',
                url: `/Facility-Group/EditStatus/${updateId}`,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    update_status: selectedValue
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