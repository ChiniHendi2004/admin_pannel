@extends('layouts.app')

@section('pagetitle')
Update || Master
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Update Master</li>
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
            <div class="col-lg-4 ">
                <div class="card p-3">
                    <form method="POST" id="myForm">

                        @csrf
                        <div>

                            <div class="mb-3">
                                <label for="update_type" class="form-label">Update Name</label>
                                <input type="text" class="form-control" id="update_type" placeholder="Enter update Type Name" name="update_type" value="{{ old('update_type') }}">
                            </div>

                        </div>
                        <div>

                            <div class="mb-3">
                                <label for="update_desc" class="form-label">Description</label>
                                <textarea class="form-control" id="update_desc" name="update_desc" placeholder="Enter your Message" aria-placeholder="Enter Content" required value="{{ old('update_desc') }}"></textarea>
                                <small id="error-roleDesc" class="text-danger"></small>
                            </div>

                        </div>
                        <div>
                            <button type="submit" class="btn text-white"
                                style="background-color: #00008B">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body table-responsive p-0" style="height: 338px">
                        <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Update's Name</th>
                                    <th>Desc</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- Edit Modal --}}
<div class="modal fade" id="editModal">
    <div class="modal-dialog d-flex ">
        <div class="modal-content">
            <form action="{{ url('/Updates-Master/Edit') }} " method="POST" id="updateupdateForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title-update"> Update</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id" value="" /> 
                    <div>

                        <div class="mb-3">
                            <label for="update_type_update" class="form-label">Update Name</label>
                            <input type="text" class="form-control" id="update_type_update" placeholder="Enter update Type Name" name="update_type" value="{{ old('update_type') }}">
                        </div>

                    </div>
                    <div>

                        <div class="mb-3">
                            <label for="update_desc_update" class="form-label">Description</label>
                            <textarea class="form-control" id="update_desc_update" name="update_desc" placeholder="Enter your Message" aria-placeholder="Enter Content" required value="{{ old('update_desc') }}"></textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" style="background-color: #00008B">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="myModaldel">
    <div class="modal-dialog d-flex ">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title">Title</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="d-flex justify-content-center">Are Sure want to delete </h4>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@section('scripts')
<script>
    function clearTable() {
        $('#data-table tbody').empty();
    }

    // get table data 
    function fetchDataAndPopulateTable() {
        clearTable();
        $.ajax({
            // url: '/Updates-Master/List',
            url: "{{ url('/Updates-Master/List') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var counter = 1;
                $.each(response.data, function(index, item) {
                    var statusSelect =
                        '<select class="custom-select select-status" data-id="' + item
                        .update_id + '">' +
                        '<option value="1" ' + (item.update_status == 1 ? 'selected' : '') +
                        '>Active</option>' +
                        '<option value="0" ' + (item.update_status == 0 ? 'selected' : '') +
                        '>Inactive</option>' +
                        '</select>';
                    var editButton =
                        '<button class="btn py-2 mr-2 rounded editbtn text-white" data-update-id="' +
                        item.update_id +
                        '" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit"></i> </button>';
                    var deleteButton =
                        '<button class="btn py-2 rounded deletebtn text-white" data-update-id="' +
                        item
                        .update_id +
                        '" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt"></i> </button>';
                    var row = '<tr><td>' + counter + '</td><td>' +
                        item.update_type + '</td><td>' +
                        item.update_desc + '</td><td>' +
                        statusSelect + '</td><td>' +
                        editButton + deleteButton

                    '</td></tr>';
                    $('#data-table tbody').append(row);
                    counter++;
                });


            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }

    // form submit 
    $(document).ready(function() {
        $('#myForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                // url: '/Updates-Master/Add',
                url: "{{ url('/Updates-Master/Add') }}",
                data: $('#myForm').serialize(),
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' +
                        response.message + '</div>');
                    $('#myForm')[0].reset(); // Clear the form
                    fetchDataAndPopulateTable()
                },
                error: function(error) {
                    $('#responseMessage').html(
                        '<div class="alert alert-danger">Error submitting the form</div>'
                    );
                }
            });
        });
    });

    // table fatch 
    $(document).ready(function() {
        fetchDataAndPopulateTable()
    });

    // get details 
    $(document).on('click', '.editbtn', function() {
        var update_id = $(this).data('update-id');
        $('#update_id').val(update_id);
        $('#editModal').modal('show');
        $.ajax({
            type: "GET",
            // url: "/Updates-Master/Select/" + update_id,
            url: `{{ url('/Updates-Master/Select/${update_id}') }}`,
            success: function(response) {
                $('#update_type_update').val(response.data.update_type);
                $('#update_desc_update').val(response.data.update_desc);
                $('.modal-title-update').html("Update " + response.data.update_type);

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

    });

    // show delete modal 
    $(document).on('click', '.deletebtn', function() {
        var update_id = $(this).data('update-id');
        $('#update_id').val(update_id);
        $('#myModaldel').modal('show');

        $.ajax({
            type: "GET",
            //   url: "/Updates-Master/Select/" + update_id,
            url: `{{ url('/Updates-Master/Select/${update_id}') }}`,
            success: function(response) {
                $('.modal-title').html(response.data.update_type)

            },
        });
    });

    // delete modal complete 
    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var update_id = $('#update_id').val();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            // url: "/Updates-Master/Remove/" + update_id,
            url: `{{ url('/Updates-Master/Remove/${update_id}') }}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                fetchDataAndPopulateTable(); // Assuming you have a function to reload the table
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // update deatils 
    $('#updateupdateForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: "PUT",
            // url: "/Updates-Master/Edit",
            url: "{{ url('/Updates-Master/Edit') }}",
            data: formData,
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                $('#editModal').modal('hide');
                // console.log('AJAX request successful');
                fetchDataAndPopulateTable()
            },
            error: function(xhr, status, error) {
                console.error(error);
                // console.log(formData);
            }
        });
    });

    // patch update 
    $('#data-table').on('change', '.select-status', function() {
        var selectElement = $(this);
        var updateId = selectElement.data('id');
        var selectedValue = selectElement.val();
        console.log('update_id ', updateId);
        console.log('selectedValue ', selectedValue);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "PATCH",
            // url: "/Updates-Master/EditStatus/" + updateId,
            url: `{{ url('/Updates-Master/EditStatus/${updateId}') }}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                update_status: selectedValue
            },
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
            }
        });




    })
</script>
@endsection






@endsection