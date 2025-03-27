@extends('layouts.app')

@section('pagetitle')
Gallery || Master
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gallery Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gallery Master</li>
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
                                <label for="sds" class="form-label">Album Name</label>
                                <input type="text" class="form-control" id="sds" placeholder="Enter Album Type Name" name="album_type" value="{{ old('album_name') }}">
                            </div>
                        </div>

                        <div>
                            <div class="mb-3">
                                <label for="album_desc" class="form-label">Description</label>
                                <textarea class="form-control" id="album_desc" name="album_desc" placeholder="Enter your Message" required value="{{ old('album_desc') }}"></textarea>
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
                                    <th>Album Name</th>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog d-flex ">
        <div class="modal-content">
            <form action="{{ url('/AlbumMaster/update') }} " method="POST" id="updateAlbumForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title-update"> Update Album</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" name="album_id" id="album_id" value="" />

                    <div>
                        <div class="mb-3">
                            <label for="album_type_update" class="form-label">Album Name</label>
                            <input type="text" class="form-control" id="album_type_update" placeholder="Enter Album Type Name" name="album_update_name" value="{{ old('album_update_name') }}">
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-3">
                            <label for="album_desc_update" class="form-label">Description</label>
                            <textarea class="form-control" id="album_desc_update" name="album_update_desc" placeholder="Enter your Message" required value="{{ old('album_update_desc') }}"></textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" style="background-color: #00008B">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </form>
        </div>
    </div>
</div>
</div>



<div class="modal fade" id="myModaldel" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog d-flex ">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title">Title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="d-flex justify-content-center">Are Sure want to delete </h4>
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

    // get table data 
    function fetchDataAndPopulateTable() {
        clearTable();
        $.ajax({
            url: '/AlbumMaster/getall',
            url: "{{ url('/AlbumMaster/getall') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var counter = 1;
                $.each(response.data, function(index, item) {
                    var statusSelect =
                        '<select class="custom-select select-status" data-id="' + item
                        .album_id + '">' +
                        '<option value="1" ' + (item.status == 1 ? 'selected' : '') +
                        '>Active</option>' +
                        '<option value="0" ' + (item.status == 0 ? 'selected' : '') +
                        '>Inactive</option>' +
                        '</select>';
                    var editButton =
                        '<button class="btn py-2 mr-2 rounded editbtn text-white" data-album-id="' +
                        item.album_id +
                        '" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit"></i> </button>';
                    var deleteButton =
                        '<button class="btn py-2 rounded deletebtn text-white" data-album-id="' +
                        item
                        .album_id +
                        '" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt"></i> </button>';
                    var row = '<tr><td>' + counter + '</td><td class="responsive-text">' +
                        item.album_type + '</td><td class="responsive-text">' +
                        item.album_desc + '</td><td>' +
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

    $(document).ready(function() {
        fetchDataAndPopulateTable()
    });

    // get details 
    $(document).on('click', '.editbtn', function() {
        var album_id = $(this).data('album-id');
        $('#album_id').val(album_id);
        $('#editModal').modal('show');
        $.ajax({
            type: "GET",
            // url: "/AlbumMaster/get/" + album_id,
            url: `{{ url('/AlbumMaster/get/${album_id}') }}`,
            success: function(response) {
                $('#album_type_update').val(response.data.album_type);
                $('#album_desc_update').val(response.data.album_desc);
                $('.modal-title-update').html("Update " + response.data.album_type);

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

    });

    // show delete modal 
    $(document).on('click', '.deletebtn', function() {
        var album_id = $(this).data('album-id');
        $('#album_id').val(album_id);
        $('#myModaldel').modal('show');

        $.ajax({
            type: "GET",
            //   url: "/AlbumMaster/get/" + album_id,
            url: `{{ url('/AlbumMaster/get/${album_id}') }}`,
            success: function(response) {
                console.log(response.data);
                $('.modal-title').html(response.data.album_type)

            },
        });
    });
    // delete modal complete 
    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var album_id = $('#album_id').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            // url: "/AlbumMaster/delete/" + album_id,
            url: `/AlbumMaster/delete/${album_id}`,
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
    $('#updateAlbumForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: "PUT",
            // url: "/AlbumMaster/update",
            url: "{{ url('/AlbumMaster/update') }}",
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
            }
        });
    });

    // patch album 
    $('#data-table').on('change', '.select-status', function() {
        var selectElement = $(this);
        var albumId = selectElement.data('id');
        var selectedValue = selectElement.val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "PATCH",
            // url: "/AlbumMaster/patch/" + albumId,
            url: `{{ url('/AlbumMaster/patch/${albumId}') }}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                status: selectedValue
            },
            dataType: "json",
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
            }
        });




    })

    // form submit 
    $(document).ready(function() {
        $('#myForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                // url: '/AlbumMaster',
                url: "{{ url('/AlbumMaster') }}",
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
</script>
@endsection






@endsection