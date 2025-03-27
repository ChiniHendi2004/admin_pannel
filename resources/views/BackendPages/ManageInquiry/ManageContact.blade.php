@extends('layouts.app')

@section('pagetitle')
Contact || List
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h3 class="m-0">Contact List</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact List</li>
                        </ol>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div id="responseMessage"></div>
    <div class="content px-2">
        <div class="card">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Create-Contact/Page') }}">
                    <button type="button" class="btn text-white" style="background-color: #00008B">
                        <i class="fas fa-plus-circle me-2"></i>Add new
                    </button>
                </a>
            </div>
        </div>

    </div>
    <div id="data-table">
        <!-- /.card-header -->

        <!-- /.card-body -->
    </div>
</div>
</div>

@section('scripts')
<script>
    // Data formatting
    function updatesList() {
        $('#data-table tbody').empty();

        $.ajax({
            type: "GET",
            url: `/Manage-Contact/List`,
            dataType: "json",
            success: function(response) {
                var list = response.data;
                var counter = 1;

                // For each item in the response, create rows dynamically
                $.each(list, function(index, item) {
                    // Table row
                    var statusSelect = `
                    <select class="form-select select-status" data-id="${item.id }">
                        <option value="1" ${item.status == 1 ? "selected" : ""}>Active</option>
                        <option value="0" ${item.status == 0 ? "selected" : ""}>Inactive</option>
                    </select>`;
                    var viewContent = `
                    <a href="/Manage-Contact/Update/Page/${item.id }" class="btn btn-success btn-sm ms-4 ">
                        <i class="far fa-eye"></i>
                    </a>`;
                    var deleteButton = `<button class="btn btn-sm btn-danger deletebtn" data-edit-id="${item.id }"><i class="fas fa-trash-alt"></i></button>`;
                    var row = `
            <div class="card table-responsive px-4" >
                <table class="table table-bordered table-hover mt-4">
                    <thead class="">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>${counter}</td>
                        <td class="responsive-text">${item.visitor_name}</td>
                        <td>${item.visitor_email}</td>
                        <td>${item.visitor_phone}</td>
                        <td>${statusSelect}</td>
                        <td>${viewContent} ${deleteButton}</td>
                    </tr>
                    </tbody>
                </table>
                    <div >
                       <h5  style="font-weight: 700;">Description:</h5>
                       <p>${item.complaint_status}</p>
                    </div>
            </div>
                   `;

                    $('#data-table').append(row);
                    counter++;
                });

                // Reinitialize DataTable
                $('#data-table').DataTable({
                    deferRender: true,
                    processing: true,
                    ordering: true,
                    searching: true,
                });
            },
            error: function(error) {
                console.error(error);
            },
        });
    }

    $(document).ready(function() {
        updatesList();
    });

    $('#data-table').on('change', '.select-status', function() {
        var selectElement = $(this);
        var testi_id = selectElement.data('id');
        var selectedValue = selectElement.val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "PATCH",
            url: `EditStatus/${testi_id}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                status: selectedValue
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