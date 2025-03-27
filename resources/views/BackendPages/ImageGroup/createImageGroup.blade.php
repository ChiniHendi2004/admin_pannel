@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div id="responseMessage"></div>

    <div class="row">
        <!-- Left: Form to Create Image Group -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Create Image Group</div>
                <div class="card-body">
                    <form id="imageGroupForm">
                        @csrf
                        <div class="mb-3">
                            <label for="group_name" class="form-label">Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="group_name" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Create Group</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: List of Image Groups -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Image Groups</div>
                <div class="card-body">
                    <table class="table table-bordered" id="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Group Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically via AJAX -->
                        </tbody>
                    </table>
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
                <h5 class="modal-title" id="editModalLabel">Edit Image Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editImageGroupForm">
                    @csrf
                    <input type="hidden" id="editGroupId">
                    <div class="mb-3">
                        <label for="editGroupName" class="form-label">Group Name</label>
                        <input type="text" class="form-control" id="editGroupName" required>
                    </div>
                    <button type="button" id="saveEditButton" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        fetchImageGroups();

        // Fetch Image Groups
        function fetchImageGroups() {
            $.ajax({
                url: "{{ url('/image-groups') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $("#data-table tbody").empty();
                    response.data.forEach((item, index) => {
                        $("#data-table tbody").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.group_name}</td>
                                <td>
                                    <select class="form-select select-status" data-id="${item.id}">
                                        <option value="1" ${item.status == 1 ? "selected" : ""}>Active</option>
                                        <option value="0" ${item.status == 0 ? "selected" : ""}>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-group" data-id="${item.id}" data-group-name="${item.group_name}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger delete-group" data-id="${item.id}"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function() {
                    alert("Error fetching image groups.");
                }
            });
        }

        // Create Image Group
        $("#imageGroupForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('/create-image-group') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                    $("#imageGroupForm")[0].reset();
                    fetchImageGroups();
                },
                error: function() {
                    alert("Error creating image group.");
                }
            });
        });

        // Open Edit Modal
        $(document).on("click", ".edit-group", function() {
            var groupId = $(this).data("id");
            var groupName = $(this).data("group-name");

            $("#editGroupId").val(groupId);
            $("#editGroupName").val(groupName);
            $("#editModal").modal("show");
        });

        // Save Edit Changes
        $("#saveEditButton").click(function() {
            var groupId = $("#editGroupId").val();
            var newGroupName = $("#editGroupName").val();

            $.ajax({
                url: `/update-image-group/${groupId}`,
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    group_name: newGroupName
                },
                success: function(response) {
                    $("#editModal").modal("hide");
                    fetchImageGroups();
                    $("#responseMessage").html('<div class="alert alert-primary">' + response.message + '</div>');
                },
                error: function() {
                    alert("Error updating image group.");
                }
            });
        });

        // Delete Image Group
        $(document).on("click", ".delete-group", function() {
            var groupId = $(this).data("id");

            if (confirm("Are you sure you want to delete this group?")) {
                $.ajax({
                    url: `/delete-image-group/${groupId}`,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        fetchImageGroups();
                        $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                    },
                    error: function() {
                        alert("Error deleting image group.");
                    }
                });
            }
        });

        // Update Status
        $(document).on("change", ".select-status", function() {
            var groupId = $(this).data("id");
            var newStatus = $(this).val();

            $.ajax({
                url: `/update-status/${groupId}`,
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: newStatus
                },
                success: function(response) {
                    $("#responseMessage").html('<div class="alert alert-info">' + response.message + '</div>');
                },
                error: function() {
                    alert("Error updating status.");
                }
            });
        });
    });
</script>
@endsection