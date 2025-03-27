@extends('layouts.app')

@section('pagetitle')
Updates || View
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View Content</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>

        <input type="hidden" id="updates_id" value="{{ $id }}">

        <div class="card p-3">
            <div class="edit-content">
                <div class="d-flex justify-content-end" id="edit_title">

                </div>
                <h1 id="title_text"></h1>
                <strong id="blog_date"></strong>
                <p></p>
                <div id="blog_img"></div>
            </div>
            <div class="show_content">

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
                <h4 class="d-flex justify-content-center">Are Sure Want To Delete This Content <span id="content_no"></span></h4>
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
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }
    var updates_id = $('#updates_id').val();

    function selecttitle() {
        var title = $('#edit_title');
        $.ajax({
            type: "GET",
            url: `{{ url('/Updates/Select/${updates_id}')}}`,
            dataType: "json",
            success: function(response) {
                var url = `${response.updates.display_image}`;
                $('#title_text').html(response.updates.title);
                $('#blog_date').html(formatDate(response.updates.event_date));
                $('#blog_img').html(` 
                    <img src="${url}" class="thumbnail rounded" width="300">
                `);
                $('#showImg').html(
                    `<img class="rounded" src="${response.updates.display_image}" alt="" width="350px" height="200px">`
                );
                title.append(`
                <a href="{{ url('/Edit/Updates/Page/${response.updates.id}') }}" class="edit-paragraph-link">
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
            url: `{{ url('/Updates-Details/List/${updates_id}') }}`,
            dataType: "json",
            success: function(response) {
                console.log(response);
                var content = $('.show_content'); // Use the correct selector
                content.empty();
                if (response.data.length > 0) {
                    for (let i = 0; i < response.data.length; i++) {
                        var list = response.data[i];
                        var content_list = `
                    <div class="edit-content">
                     <div class="d-flex justify-content-end">
                         <div class="mx-2">
                             <a href="{{ url('/View/Updates/Single/Content/Page/${list.update_id}/${list.paragraph_no}') }}" class="edit-paragraph-link">
                                 <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                                         class="far fa-edit"></i></button>
                             </a>
                         </div>
                         <div>
                             <a href="#" class="edit-paragraph-link deletebtn" data-delete-id = "${list.paragraph_no}">
                                 <button type="submit" class="btn text-white" style="background-color: #e51010"><i
                                         class="fas fa-trash-alt"></i></button>
                             </a>
                         </div>
                     </div>
                    <div id="content_dataText" class="my-2">${list.paragraph_text}</div>
                           
                    </div>
                    `;


                        content.append(content_list); // Use the correct selector
                    }
                }
            }
        });
    }

    $(document).on('click', '.deletebtn', function() {
        var delete_id = $(this).data('delete-id');
        $('#myModaldel').modal('show');
        $('#content_no').append(delete_id);
        $('#myModaldel').on('click', '#delete-confirm-button', function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "DELETE",
                url: `{{ url('/Updates-Details/Remove/${delete_id}') }}`,
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