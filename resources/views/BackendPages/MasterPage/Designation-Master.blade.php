@extends('layouts.app')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Designation Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Designation Master</li>
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
            <div class="col-lg-4">
                <div class="card p-3">
                    <form method="POST" id="myForm">
                        @csrf
                        <div class="mb-3">
                            <label for="designation_type" class="form-label">Designation Name</label>
                            <input type="text" class="form-control" placeholder="Enter designation Type Name" id="designation_type" name="designation_type" value="{{ old('designation_type') }}" />
                        </div>
                        <div class="mb-3">
                            <label for="designation_desc" class="form-label">Description</label>
                            <input type="text" class="form-control" placeholder="Enter your Message" id="designation_desc" value="{{ old('designation_desc') }}" name="designation_desc" />
                        </div>
                        <div>
                            <button type="submit" class="btn text-white" style="background-color: #00008B">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body table-responsive p-0" style="height: 600px">
                        <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Designation Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('/designationMaster/update') }}" method="POST" id="updatedesignationForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title-update">Update Designation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" name="designation_id" id="designation_id" value="" />
                    <div class="mb-3">
                        <label for="designation_type_update" class="form-label">Designation Name</label>
                        <input type="text" class="form-control" placeholder="Enter designation Type Name" id="designation_type_update" name="designation_type" value="{{ old('designation_type') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="designation_desc_update" class="form-label">Description</label>
                        <input type="text" class="form-control" placeholder="Enter your Message" id="designation_desc_update" name="designation_desc" value="{{ old('designation_desc') }}" />
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" style="background-color: #00008B">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myModaldel">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title">Delete Confirmation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="d-flex justify-content-center">Are you sure you want to delete?</h4>
            </div>
            <!-- Modal footer -->
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

    // Get table data
    function fetchDataAndPopulateTable() {
        clearTable();
        $.ajax({
            url: "{{ url('/designation-Master/List') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var counter = 1;
                $.each(response.data, function(index, item) {
                    var statusSelect =
                        '<select class="form-select select-status" data-id="' + item.designation_id + '">' +
                        '<option value="1" ' + (item.designation_status == 1 ? 'selected' : '') + '>Active</option>' +
                        '<option value="0" ' + (item.designation_status == 0 ? 'selected' : '') + '>Inactive</option>' +
                        '</select>';
                    var editButton =
                        '<button class="btn py-2 mr-2 rounded editbtn text-white" data-designation-id="' + item.id + '" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit"></i></button>';
                    var deleteButton =
                        '<button class="btn py-2 rounded deletebtn text-white" data-designation-id="' + item.id + '" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt"></i></button>';
                    var row = '<tr><td>' + counter + '</td><td>' + item.designation_type + '</td><td>' + item.designation_desc + '</td><td>' + statusSelect + '</td><td>' + editButton + deleteButton + '</td></tr>';
                    $('#data-table tbody').append(row);
                    counter++;
                });
            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }

    // Form submit
    $(document).ready(function() {
        $('#myForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ url('/designation-Master/Add') }}",
                data: $('#myForm').serialize(),
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    $('#myForm')[0].reset(); // Clear the form
                    fetchDataAndPopulateTable();
                },
                error: function() {
                    $('#responseMessage').html('<div class="alert alert-danger">Error submitting the form</div>');
                }
            });
        });
    });

    // Table fetch
    $(document).ready(function() {
        fetchDataAndPopulateTable();
    });

    // Get details
    $(document).on('click', '.editbtn', function() {
        var designation_id = $(this).data('designation-id');
        $('#designation_id').val(designation_id);
        $('#editModal').modal('show');
        $.ajax({
            type: "GET",
            url: `{{ url('/designation-Master/Select/${designation_id}') }}`,
            success: function(response) {
                console.log(response);
                $('#designation_type_update').val(response.data.designation_type);
                $('#designation_desc_update').val(response.data.designation_desc);
                $('.modal-title-update').html("Update " + response.data.designation_type);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Show delete modal
    $(document).on('click', '.deletebtn', function() {
        var designation_id = $(this).data('designation-id');
        $('#designation_id').val(designation_id);
        $('#myModaldel').modal('show');
        $.ajax({
            type: "GET",
            url: `{{ url('/designation-Master/Select/${designation_id}') }}`,
            success: function(response) {
                $('.modal-title').html(response.data.designation_type);
            },
        });
    });

    // Delete modal complete
    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var designation_id = $('#designation_id').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `{{ url('/designation-Master/Remove/${designation_id}') }}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                fetchDataAndPopulateTable(); // Assuming you have a function to reload the table
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Update details
    $('#updatedesignationForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "PUT",
            url: "{{ url('/designation-Master/Edit') }}",
            data: formData,
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                $('#editModal').modal('hide');
                fetchDataAndPopulateTable();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Patch designation status
    $('#data-table').on('change', '.select-status', function() {
        var selectElement = $(this);
        var designationId = selectElement.data('id');
        var selectedValue = selectElement.val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "PATCH",
            url: `{{ url('/designation-Master/EditStatus/${designationId}') }}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                designation_status: selectedValue
            },
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
            }
        });
    });
</script>
@endsection

@endsection
