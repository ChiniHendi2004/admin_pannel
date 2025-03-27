@extends('layouts.app')

@section('pagetitle')
Testimonial || List
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">Manage Testimonial</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage Testimonial</li>
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
                <a href="{{ route('getTestimonialsPage') }}">
                    <button type="button" class="btn text-white" style="background-color: #00008B">
                        <i class="fas fa-plus-circle me-2"></i>Add new </button>
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
            month: 'numeric',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    function TestimonialList() {
        testi_id = '1';
        var cardContainer = $('#cardContainer');
        cardContainer.empty();
        $.ajax({
            type: "GET",
            url: `/Manage-Testimonial/List/${testi_id}`,
            success: function(response) {
                console.log(response);
                var nodata_card = $(`<div class="card text-center mx-4" style="width:100%; height:455px; background-color: #00008B;">
            <h1 style="padding:15%;" class="d-flex justify-content-center text-white" >Create New Testimonial </h1>
          </div>`)

                if (response.data.length === 0) {
                    cardContainer.append(nodata_card)
                } else {
                    $.each(response.data, function(index, item) {
                        var card = `
                   <div class="col-lg-6">
                     <div class="card mb-3" style="">
                      <div class="row no-gutters">
                      <div class="col-md-4 p-4">
                        <img src="${item.photo_url}" alt="${item.name}" class="img-thumbnail" style="max-width: 200px;">
                      </div>
                      <div class="col-md-8 ps-4">
                        <div class="card-body">
                          <h5 class="card-title">${item.name}</h5>
                          <h6 class="card-text">Message: ${item.testimonial_text}</h6>
                          <h6>Web URL: <a href="${item.website_url}" class="card-text" target="_blank">${item.website_url}</a></h6>
                          <p class="card-text">Rating: ${item.rating}</p>
                          <p class="card-text"><small class="text-muted">Created On ${formatDate(item.created_at)}</small></p>
                          <p class="card-text"><small class="text-muted">expired On ${formatDate(item.expiration_date)}</small></p>
                          <div class="d-flex">
                            <a href="{{url('/Manage-Testimonial/Update/Page/${item.id}')}}"class="btn text-white me-2" style="background-color: #00008B"><i class="fas fa-edit text-white me-2"></i> Edit</a>
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
        delete_id = testi_id; // Fixed variable usage
        $('#myModaldel').modal('show');
        $.ajax({
            type: "GET",
            url: `/Manage-Testimonial/Select/${testi_id}`, // Wrapped URL in backticks
            success: function(response) {
                if (response.data) {
                    $('.modal-title').html(response.data.name);
                } else {
                    $('.modal-title').html('No data found');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "DELETE",
            url: `/Manage-Testimonial/Remove/${delete_id}`, // Wrapped URL in backticks
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                TestimonialList();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                $('#responseMessage').html('<div class="alert alert-danger">Failed to delete testimonial.</div>');
            }
        });
    });
</script>



@endsection




@endsection