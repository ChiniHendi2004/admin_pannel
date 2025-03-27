@extends('layouts.app')

@section('pagecommittee_name')
View Committee
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-2" style="color: black;">View Committee</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Committee</li>
                        </ol>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>

        <input type="hidden" id="committee_id" value="{{ $id }}">

        <div class="card mx-2 p-3">
            <div class="edit-content">
                <div class="d-flex justify-content-end" id="edit_Class">

                </div>
                <h1 id="committee_name_name"></h1>
                <strong id="short_desc"></strong>

            </div>
            <div class="show_details">

            </div>


        </div>
    </div>
</div>

<div class="modal fade" id="myModaldel">
    <div class="modal-dialog d-flex ">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="d-flex justify-content-center">Are Sure Want To Delete This Content <span id="paragraph_order"></span></h4>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c">Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style scoped>
    .edit-content:hover {
        border: 2px solid #00008B;
        border-radius: 8px;
        padding: 5px;
        margin-top: 12px
    }

    .edit-paragraph-link {
        display: none;
    }

    .edit-content:hover .edit-paragraph-link {
        display: block;
    }
</style>

@section('scripts')

<script>
    var committee_id = $('#committee_id').val();

    function selectcommittee_name() {
        var committee_name = $('#edit_Class');
        $.ajax({
            type: "GET",
            url: `/Committee/Select/${committee_id}`,
            dataType: "json",
            success: function(response) {
                $('#committee_name_name').html(response.data.committee_name);
                $('#short_desc').html(response.data.short_description);
                $('#file_path').attr('src', response.data.file_path).show();
                committee_name.append(`
                <a href="{{ url('/Edit/Committee/Page/${response.data.id}') }}" class="edit-paragraph-link">
                        <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                                class="far fa-edit"></i></button>
                    </a>
                `)
            }
        });
    }

    function Content() {
        $.ajax({
            type: "GET",
            url: `/Committee/Details/List/${committee_id}`,
            dataType: "json",
            success: function(response) {
                var content = $('.show_details'); // Use the correct selector
                content.empty(); // Clear previous content
                if (response.data.length > 0) {
                    for (let i = 0; i < response.data.length; i++) {
                        var list = response.data[i];
                        var content_list = `
                    <div class="edit-content">
                        <div class="d-flex justify-content-end">
                            <div class="mx-2">
                                <a href="{{ url('/View/Committee/single/Details/Page/${committee_id}/${list.paragraph_order}') }}" class="edit-paragraph-link">
                                    <button type="button" class="btn text-white" style="background-color: #00008B">
                                        <i class="far fa-edit"></i>
                                    </button>
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn text-white deletebtn" style="background-color: #e51010" data-delete-id="${list.paragraph_order}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div id="content_dataText" class="my-2">${list.paragraph_text}</div>
                    </div>
                    `;
                        content.append(content_list); // Append the new content
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching content:", xhr.responseText);
            }
        });
    }


    $(document).on('click', '.deletebtn', function() {
        var delete_id = $(this).data('delete-id');
        $('#myModaldel').modal('show');
        $('#paragraph_order').append(delete_id);
        $('#myModaldel').on('click', '#delete-confirm-button', function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "DELETE",
                url: `{{ url('/Delete/Committee/Details/${delete_id}') }}`,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#myModaldel').modal('hide');
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');
                    Content();

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });




    $(document).ready(function() {
        selectcommittee_name();
        Content();
    });
</script>

@endsection

@endsection