@extends('layouts.app')

@section('pagetitle')
Facility || View
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="mb-2" style="color: black;">View Facilities</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Facilities</li>
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

        <input type="hidden" id="facility_id" value="{{ $id }}">

        <div class="card mx-2 p-3">
            <div class="edit-content">
                <div class="d-flex justify-content-end" id="edit_Class">

                </div>
                <h1 id="class_name"></h1>
                <strong id="blog_desc"></strong>
                <p></p>
                <div id="blog_img"></div>
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
    $('#facility_image').on('input', function() {
        const imageUrl = $(this).val();
        if (imageUrl) {
            $('#blog_img').attr('src', imageUrl).show();
        } else {
            $('#blog_img').hide();
        }
    });

    var facility_id = $('#facility_id').val();

    function selecttitle() {
        var title = $('#edit_Class');
        $.ajax({
            type: "GET",
            url: `/Facility/Get/Class/${facility_id}`,
            dataType: "json",
            success: function(response) {
                $('#class_name').html(response.data.facility_name);
                $('#blog_desc').html(response.data.short_description);
                $('#blog_img').html(` 
                    <img src="${response.data.facility_image}" class="thumbnail rounded" width="450" height="200">
                `).show();
                title.append(`
                <a href="{{ url('/Edit-Facility/${response.data.id}') }}" class="edit-paragraph-link">
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
            url: `/Edit-Facility/Details/List/${facility_id}`,
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
                                <a href="{{ url('/View-Facility/single/Details/Page/${facility_id}/${list.paragraph_order}') }}" class="edit-paragraph-link">
                                    <button type="button" class="btn text-white" style="background-color: #00008B">
                                        <i class="far fa-edit"></i>
                                    </button>
                                </a>
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
                url: `{{ url('/Updates-Content/Remove/${delete_id}') }}`,
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
        selecttitle();
        Content();
    });
</script>

@endsection

@endsection