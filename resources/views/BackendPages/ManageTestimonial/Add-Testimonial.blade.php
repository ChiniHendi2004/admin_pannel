@extends('layouts.app')

@section('pagetitle')
Testimonial || Add
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-0">Add Testimonial</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Testimonial</li>
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

                    <div class="card ">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="testimonial_form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="persone_rating" class="form-label">Rating</label>
                                            <input type="number" class="form-control" id="persone_rating" placeholder="Enter Rating value here" name="rating" min="1" max="5" required value="{{ old('persone_rating') }}">
                                            <small id="error-roleDesc" class="text-danger"></small>
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
                                    <div class="col-md-12 mb-3">
                                        <label for="desc_one" class="form-label">Testimonial Message</label>
                                        <textarea class="form-control" id="desc_one" name="testimonial_text" placeholder="Enter Your Message Here" aria-placeholder="Write About Your Achivement" required></textarea>
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
                    <p class="d-flex justify-content-center font-weight-bold pt-2">Review Section</p>
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
    // function updateLabel() {
    //     const fileInput = document.getElementById('customFile');
    //     const label = document.querySelector('.custom-file-label');

    //     if (fileInput.files.length > 0) {
    //         label.textContent = fileInput.files[0].name;
    //     } else {
    //         label.textContent = 'Choose file';
    //     }
    // }
</script>
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



    $(document).ready(function() {
        testimonialList();
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
            var formData = new FormData(this);
            formData.append('testimonial_type', 'Testimonial');

            var form = this;
            $.ajax({
                type: "POST",
                url: `/Manage-Testimonial/Add`,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $("#imagePreview img").attr('src', '').hide();
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');
                    form.reset();
                    testimonialList();

                },
                error: function(error) {

                    console.error(error);
                },
            });
        });
    };

    function testimonialList() {
        var listContainer = $('#card_list')
        listContainer.empty();
        $.ajax({
            type: "GET",
            url: `/Manage-Testimonial/List/latest`,
            dataType: "json",
            success: function(response) {
                console.log(response);
                $.each(response.data, function(index, item) {
                    var card = `
                <div class="card mb-3" style="">
                    <div class="row no-gutters">
                      <div class="col-md-4 p-4">
                        <img src="${item.photo_url}" alt="${item.name}" class="img-thumbnail" style="max-width: 200px;">
                      </div>
                      <div class="col-md-8 ps-4">
                        <div class="card-body">
                          <h5 class="card-title">${item.name}</h5>
                          <h6 class="card-text">${item.testimonial_text}</h6>
                          <p class="card-text">Rating: ${generateStars(item.rating)}</p>
                          <p class="card-text"><small class="text-muted">Created On : <b>${formatDate(item.created_at)}</b></small></p>
                          <p class="card-text"><small class="text-muted">expired On : <b>${formatDate(item.expiration_date)}</b></small></p>
                        </div>
                      </div>
                    </div>
                </div>

                `;
                    listContainer.append(card);
                });
            }
        });
    }

    function generateStars(rating) {
        let stars = '';
        const fullStars = Math.floor(rating);
        const halfStar = rating % 1 !== 0;
        const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);

        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star text-warning"></i> ';
        }
        if (halfStar) {
            stars += '<i class="fas fa-star-half-alt text-warning"></i> ';
        }
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star text-warning"></i> ';
        }

        return stars;
    }
</script>






@endsection

@endsection