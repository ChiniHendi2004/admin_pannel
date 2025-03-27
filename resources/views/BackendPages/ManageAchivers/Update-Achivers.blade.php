@extends('layouts.app')

@section('pagetitle')
Achivers || Update
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-0">Update Achievers</h3> 
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Achievers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content px-2">
        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-6  ">
                <div class="">
                    <div class="card align-middle">
                        <p class="d-flex justify-content-center font-weight-bold pt-2">Edit Detais</p>
                    </div>
                    <div class="card ">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="testimonial_form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-3">
                                <input type="hidden" name="testi_id" id="testi_id" value="{{$id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name" name="company_name" value="{{ old('company_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rank" class="form-label">Rank</label>
                                            <input type="number" class="form-control" id="rank" name="rank" min="1" max="10" placeholder="Enter Your Rank" required value="{{ old('rank') }}">
                                            <small id="error-roleDesc" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="website_url" class="form-label">Website URL</label>
                                            <input type="url" class="form-control" id="website_url" placeholder="https://atreyawebs.com" name="website_url" value="{{ old('website_url') }}" readonly>
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-12">
                                        <label for="person_img" class="form-label">Select Image</label>
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control" id="person_img" name="person_img"
                                                onchange="updateLabel(); previewImage();" accept="image/*">
                                        </div>
                                        <div id="imagePreview"></div>
                                    </div> -->
                                    <div class="col-md-12 mb-3">
                                        <label for="achievement_details" class="form-label">Achivement Details</label>
                                        <textarea class="form-control" id="achievement_details" name="achievement_details" rows="2" placeholder="Write About Your Achivement" aria-placeholder="Write About Your Achivement" required value="{{ old('achievement_details') }}"></textarea>
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>

                                </div>
                                <button type="submit" class="btn text-white submitalbum" style="background-color: #00008B">Submit</button>

                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-lg-6 ">
                <div class="card align-middle">
                    <p class="d-flex justify-content-center font-weight-bold pt-2">View Section </p>
                </div>
                <div id="card-list">

                </div>
            </div>
        </div>

    </div>
</div>



@section('scripts')
<script>
    $(document).ready(function() {
        testimonialDetails();
        addTestimonials();
    });

    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    function addTestimonials() {
        $("#testimonial_form").submit(function(event) {
            event.preventDefault();
            var testi_id = $('#testi_id').val();
            var formData = new FormData(this);
            formData.append("_method", "PUT");
            var form = this;
            // $(".custom-file-label").text('Choose file');
            // $("#imagePreview").empty();
            $.ajax({
                type: "POST",
                url: `/Manage-Achivers/Update/${testi_id}`,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');
                    form.reset();
                    testimonialDetails();

                },
                error: function(error) {

                    console.error(error);
                },
            });
        });
    };

    function testimonialDetails() {
        var Container = $('#card-list')
        Container.empty();
        var testi_id = $('#testi_id').val();
        $.ajax({
            type: "GET",
            url: `/Manage-Achivers/Select/${testi_id}`,
            dataType: "json",
            success: function(response) {
                $('#name').val(response.data.name);
                $('#company_name').val(response.data.company_name);

                $('#rank').val(response.data.rank);
                $('#achievement_details').val(response.data.achievement_details);
                var card = `
                <div class="card" style="">
                    <div class="row no-gutters">
                      <div class="col-md-8">
                        <div class="card-body">
                          <h5 class="card-title">${response.data.name}</h5>
                          <h6 class="card-text">Company: ${response.data.company_name}</h6>                        
                          <h6 class="card-text">Rank: ${response.data.rank}</h6>
                          <h6 class="card-text">Achivement: ${response.data.achievement_details}</h6>
                          <p class="card-text"><small class="text-muted">Created On ${formatDate(response.data.created_at)}</small></p>                         
                        </div>
                      </div>
                    </div>
                </div>
                
                `;
                Container.append(card);

            }
        });
    }
</script>

@endsection

@endsection