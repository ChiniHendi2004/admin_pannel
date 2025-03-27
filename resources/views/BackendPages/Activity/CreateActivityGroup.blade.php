@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Activity Groups</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Activity Groups</li>
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
            <!-- Form Section -->
            <div class="col-lg-4">
                <div class="card p-3 shadow-sm">
                    <form method="POST" id="activityGroupForm">
                        @csrf
                        <div class="mb-3">
                            <label for="group_name" class="form-label">Group Name</label>
                            <input type="text" class="form-control" placeholder="Enter Group Name" id="group_name"
                                name="group_name" />
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body table-responsive p-0 mt-1 ms-2 ps-2" style="height: 600px">
                        <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th style="width: 70px;">Group Name</th>
                                    <th style="width: 70px;">Status</th>
                                    <th style="width: 70px;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Activity Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editActivityGroupForm">
                    @csrf
                    <input type="hidden" id="editGroupId" name="id">
                    <div class="mb-3">
                        <label for="edit_group_name" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="edit_group_name" name="group_name" />
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveEditButton">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Activity Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Activity group?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        let groupIdToDelete = null; // Variable to store group id for deletion
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Fetch and populate table
        function fetchActivityGroups() {
            $.ajax({
                url: "{{ url('/Activity/Group/List') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#data-table tbody').empty();
                    if (response.data && response.data.length > 0) {
                        let counter = 1; // Initialize counter
                        response.data.forEach((item, index) => {
                            var statusSelect =
                                `<select class="form-select select-status" data-id="${item.id}">
                                    <option value="1" ${item.status == 1 ? 'selected' : ''}>Active</option>
                                    <option value="0" ${item.status == 0 ? 'selected' : ''}>Inactive</option>
                                </select>`;
                            var editButton =
                                `<button class="btn btn-sm btn-primary" data-id="${item.id}" data-group-name="${item.group_name}" data-status="${item.status}">
                                    <i class="fas fa-edit"></i> 
                                </button>`;
                            var deleteButton =
                                `<button class="btn btn-sm btn-danger" data-id="${item.id}">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>`;
                            $('#data-table tbody').append(`
                                <tr>
                                    <td>${counter}</td>
                                    <td>${item.group_name}</td>
                                    <td>${statusSelect}</td>
                                    <td>${editButton} ${deleteButton}</td>
                                </tr>
                            `);
                            counter++; // Increment counter for the next row
                        });
                    }
                },
                error: function() {
                    alert('Error fetching activity groups.');
                }
            });
        }

        // Initial fetch of activity groups
        fetchActivityGroups();

        // Open Edit Modal
        $('#data-table').on('click', '.btn-primary', function() {
            var id = $(this).data('id');
            var groupName = $(this).data('group-name');
            var status = $(this).data('status');
            $('#editGroupId').val(id);
            $('#edit_group_name').val(groupName);
            $('#edit_status').val(status);
            $('#editModal').modal('show');
        });

        // Save Edited Group
        $('#saveEditButton').click(function() {
            var formData = $('#editActivityGroupForm').serialize();
            var groupId = $('#editGroupId').val();

            $.ajax({
                url: `/Edit/Activity/Group/${groupId}`,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editModal').modal('hide');
                    fetchActivityGroups();
                    $('#responseMessage').html('<div class="alert alert-primary">' + response.message + '</div>');

                },
                error: function() {
                    alert('Error updating activity group.');
                }
            });
        });

        // Open Delete Modal
        $('#data-table').on('click', '.btn-danger', function() {
            groupIdToDelete = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm Delete
        $('#confirmDeleteButton').click(function() {

            if (groupIdToDelete) {
                $.ajax({
                    url: `/Delete/Activity/Group/${groupIdToDelete}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');

                        fetchActivityGroups();

                    },
                    error: function() {
                        alert('Error deleting activity group.');
                    }
                });
            }
        });

        // Form submission to add a new activity group
        $('#activityGroupForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: "{{ url('/Create/Activity/Group') }}",
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>
                    `);
                    $('#activityGroupForm')[0].reset();
                    fetchActivityGroups();
                },
                error: function() {
                    alert('Error creating activity group.');
                }
            });
        });
    });
</script>
@endsection
@endsection