@extends('layouts.app')

@section('pagetitle')
Testimonial || Update
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-0">Update Testimonial</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Testimonial</li>
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

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="testimonial_text" class="form-label">Testimonial Message</label>
                                            <input type="text" class="form-control" id="testimonial_text" placeholder="Enter your Message Here" name="testimonial_text" value="{{ old('testimonial_text') }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="photo_url" class="form-label">Image URL</label>
                                        <div class="input-group mb-3">
                                            <input type="url" class="form-control" id="photo_url" name="photo_url" placeholder="Enter Image URL" required>
                                        </div>
                                        <div id="imagePreview">
                                            <img id="previewImg" src="" alt="Image Preview" style="width: 240px; display: none;" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating</label>
                                        <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required value="{{ old('rating') }}">
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2">Testimonial Section</p>
                </div>
                <div id="card_list">
                    {{-- <div class="card mb-3" style="max-width: 540px;">
                        <div class="row no-gutters">
                          <div class="col-md-4">
                            <img src="..." alt="...">
                          </div>
                          <div class="col-md-8">
                            <div class="card-body">
                              <h5 class="card-title">Card title</h5>
                              <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                              <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                          </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

    </div>
</div>



@section('scripts')
<script>
    document.getElementById('photo_url').addEventListener('input', function() {
        const imageUrl = this.value;
        const previewImg = document.getElementById('previewImg');

        if (imageUrl) {
            previewImg.src = imageUrl;
            previewImg.style.display = 'block';
        } else {
            previewImg.style.display = 'none';
        }
    });
</script>
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
            formData.append('testimonial_type', 'Testimonial');

            $.ajax({
                type: "POST",
                url: `/Manage-Testimonial/Update/${testi_id}`,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#imagePreview img").attr('src', '').hide();
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
        var Container = $('#card_list')
        Container.empty();
        var testi_id = $('#testi_id').val();
        $.ajax({
            type: "GET",
            url: `/Manage-Testimonial/Select/${testi_id}`,
            dataType: "json",
            success: function(response) {
                $('#name').val(response.data.name);
                $('#testimonial_text').val(response.data.testimonial_text);
                $('#photo_url').val(response.data.photo_url);
                $('#rating').val(response.data.rating);

                var card = `
                <div class="card mb-3" style="">
                    <div class="row no-gutters">
                      <div class="col-md-4 p-4">
                        <img src="${response.data.photo_url}" alt="${response.data.name}" class="img-thumbnail" style="max-width: 200px;">
                      </div>
                      <div class="col-md-8 ps-4">
                        <div class="card-body">
                          <h5 class="card-title">${response.data.name}</h5>
                          <h6 class="card-text">${response.data.testimonial_text}</h6>
                        
                          <h6 class="card-text">${response.data.rating}</h6>

                          <p class="card-text"><small class="text-muted">Created on :  &nbsp<b>${formatDate(response.data.created_at)} </b></small></p>
                        </div>
                      </div>
                    </div>
                </div>
                
                ;`
                Container.append(card);

            }
        });
    }
</script>






@endsection

@endsection