@extends('layouts.app')

@section('pagetitle')
Update || List
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h3 class="m-0">Updates List</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Updates List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div id="responseMessage"></div>
    <div class="content px-4">
        <div class=" card ">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Create/Updates/Page') }}">
                    <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                            class="fas fa-plus-circle px-2"></i>Add new</button>
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
                                <th>News heading</th>
                                <th>Date</th>
                                <th>Add Content</th>
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
                <h5 class="d-flex justify-content-center">This Will Remove All The Content's of </h5>
                <h5 class="d-flex justify-content-center" id="content-title"> </h5>
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
    // data formating
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    function updatesList() {
        $('#data-table tbody').empty();
        var list = '';

        $.ajax({
            type: "GET",
            url: `/Updates/List`,
            dataType: "json",
            success: function(response) {
                list = response.data
                var counter = 1;
                $.each(list, function(index, item) {
                    var addMore =
                        `<a href="{{ url('/Updates/Content/Page/${item.id}') }}" class="btn text-white" style="background-color: #00008B">Add More</a>`;
                    var viewContent =
                        `<a href="{{ url('/View/Updates/Content/Page/${item.id}') }}" class="btn py-2 mr-2 rounded  mx-1  " style="background-color: #16641b ;  font-size: 13px ; "><i class="far fa-eye text-white"></i></a>`
                    var editButton = `<a href="{{ url('/Edit/Updates/Page/${item.id}') }}" class="btn py-2 mr-2 rounded editbtn text-white" data-title-id="undefined" style="background-color: #00008B; font-size: 13px"><i class="fas fa-edit text-white"></i> </a>`
                    var deleteButton = `<button class="btn py-2 rounded deletebtn text-white" data-delete-id="${item.id}" style="background-color: #d70c1a; font-size: 13px"><i class="fas fa-trash-alt text-white"></i> </button>`

                    var row = '<tr><td>' + counter + '</td><td class="responsive-text">' +
                        item.title + '</td><td>' +
                        formatDate(item.event_date) + '</td><td>' +
                        addMore + '</td><td>' +
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
    var title_id;
    $(document).on('click', '.deletebtn', function() {
        title_id = $(this).data('delete-id');
        $('#myModaldel').modal('show');

        $.ajax({
            type: "GET",
            url: `{{ url('/Updates/Select/${title_id}') }}`,
            dataType: "json",
            success: function(response) {
                $('.modal-title').html(response.updates.title);
                $('#content-title').html(response.updates.title);
            }
        });
    });

    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `{{url('/Delete/Updates/${title_id}')}}`,
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