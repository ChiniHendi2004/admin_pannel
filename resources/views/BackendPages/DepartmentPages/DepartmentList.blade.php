@extends('layouts.app')

@section('pagetitle')
Department || List
@endsection

@section('content')

 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h3 class="m-0" style="color: black;">Department List</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Department List</li>
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
                <a href="{{ url('/Create-Department') }}">
                    <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                            class="fas fa-plus-circle pe-2"></i>Add new</button>
                </a>
            </div>
        </div>
        <div>
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body table-responsive p-2" style="height: 65vh;">
                    <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Class</th>
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

<div class="modal fade" id="myModaldel" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h5 class="text-center">This Will Remove All The Content's of</h5>
                <h5 class="text-center" id="details-title"></h5>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c;">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




@section('scripts')
<script>
    // data formating


    function updatesList() {
        $('#data-table tbody').empty();
        var list = '';

        $.ajax({
            type: "GET",
            url: `/Department/List`,
            dataType: "json",
            success: function(response) {
                list = response.data
                var counter = 1;
                $.each(list, function(index, item) {
                    var addDetails =
                        `<a href="{{ url('/Department-Details/Page/${item.id}') }}" class="btn text-white" style="background-color: #00008B">Add Details</a>`;
                    var viewContent =
                        `<a href="{{ url('/View-Departments/Page/${item.id}') }}" class="btn py-2 mr-2 rounded  mx-1  " style="background-color: #16641b ;  font-size: 13px ; "><i class="far fa-eye text-white"></i></a>`
                    var editButton = `<a href="{{ url('/Edit-Department/${item.id}') }}" class="btn py-2 mr-2 rounded editbtn text-white" data-title-id="undefined" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit text-white"></i> </a>`
                    var deleteButton = `<button class="btn py-2 rounded deletebtn text-white" data-delete-id="${item.id}" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt text-white"></i> </button>`

                    var row = '<tr><td>' + counter + '</td><td class="responsive-text">' +
                        item.department_name + '</td><td>' +
                        item.short_description + '</td><td>' +
                        addDetails + '</td><td>' +
                        editButton + viewContent + deleteButton

                    '</td></tr>';

                    $('#data-table tbody').append(row);

                    counter++;
                })
                $('#data-table').DataTable({
                    deferRender: true,
                    processing: true,
                    ordering: true,
                    searching: true,
                })


            }

        });

    }

    $(document).ready(function() {
        updatesList();

    });


    // show delete modal 
    var department_id;
    $(document).on('click', '.deletebtn', function() {
        department_id = $(this).data('delete-id');
        $('#myModaldel').modal('show');

        $.ajax({
            type: "GET",
            url: `/Department/Get/Title/${department_id}`,
            dataType: "json",
            success: function(response) {

                $('.modal-title').html(response.data.department_name);
                $('#details-title').html(response.data.department_name);

            }
        });
    });

    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `{{url('/Department/Remove/${department_id}')}}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                updatesList();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>

@endsection

@endsection