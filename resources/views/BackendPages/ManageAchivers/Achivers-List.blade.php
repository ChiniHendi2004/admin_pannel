@extends('layouts.app')

@section('pagetitle')
Achivers || List
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Manage Achievers</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Achievers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>

        <div class="card  ">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Manage-Achivers/Page') }}">
                    <button type="button" class="btn text-white" style="background-color: #00008B"><i
                            class="fas fa-plus-circle text-white me-2"></i>Add new </button>
                </a>
            </div>
        </div>
        <div id="responseMessage"></div>
        <div class="row" id="cardContainer">

        </div>

    </div>
    <div class="modal fade" id="myModaldel" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal body -->
                <div class="modal-body text-center">
                    <h4>Are you sure you want to delete?</h4>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c;">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>





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

    function TestimonialList() {

        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            url: `  /Manage-Achivers/List`,
            success: function(response) {
                var nodata_card = $(`<div class="card text-center " style="width:100%; height:455px; background-color: #00008B;">
            <h1 style="padding:15%;" class="d-flex justify-content-center text-white" >Create New Achivers</h1>
          </div>`)

                if (response.data.length === 0) {
                    cardContainer.append(nodata_card)
                } else {
                    $.each(response.data, function(index, item) {
                        var card = `
                 <div class="col-lg-6">
                    <div class="card mb-3" style="">
                    <div class="row no-gutters">
                    
                      <div class="col-md-8">
                        <div class="card-body">
                          <h5 class="card-title">${item.name}</h5> 
                          <h6 class="card-text">Organization: ${item.company_name}</h6>
                          <h6>Web URL: <a href="${item.website_url}" class="card-text" target="_blank">${item.website_url}</a></h6>
                          <h6 class="card-text">Rank: ${item.rank}</h6>
                          <h6 class="card-text">Achivement: ${item.achievement_details}</h6><br>

                          <i><h6 class="card-text"><small class="text-muted">Created On :<b>${formatDate(item.created_at)}</b></small></h6></i>
                          <i><h6 class="card-text"><small class="text-muted">expired On :<b>${formatDate(item.expiration_date)}</b></small></h6></i>
                          <div class="d-flex mt-3">
                            <a href="{{ url('/Manage-Achivers/Update/Page/${item.id}') }}"class="btn text-white me-2" style="background-color: #00008B"><i class="fas fa-edit text-white me-2"></i> Edit</a>
                            <a href="javascript:;"class="btn btn-danger deletebtn"  data-testimonial-id="${item.id}"><i class="fas fa-trash-alt me-2"></i>Delete</a>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
                 `;
                        cardContainer.append(card);
                    });

                }


            }
        });
    }

    $(document).ready(function() {
        TestimonialList();
    });

    var delete_id;
    $(document).on('click', '.deletebtn', function() {
        var testi_id = $(this).data('testimonial-id');
        delete_id = testi_id
        $('#myModaldel').modal('show');
        $.ajax({
            type: "GET",
            url: `/Manage-Achivers/Select/${testi_id}`,
            success: function(response) {
                $('.modal-title').html(response.data.name);
            },
        });
    });

    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        console.log(delete_id);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `/Manage-Achivers/Remove/${delete_id}`,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                    '</div>');
                TestimonialList();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>



@endsection




@endsection