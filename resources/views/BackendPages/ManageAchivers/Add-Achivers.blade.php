@extends('layouts.app')

@section('pagetitle')
Achivers || Add
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-0">Add Achievers</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Achiver's</li>
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
            <div class="col-lg-10">
                <div class="">

                    <div class="card px-2 py-2">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="testimonial_form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-3">


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
                                        <textarea class="form-control" id="achievement_details" name="achievement_details" rows="2" placeholder="Write About Your Achivement" aria-placeholder="Write About Your Achivement" required></textarea>
                                        <small id="error-roleDesc" class="text-danger"></small>
                                    </div>

                                </div>
                                <button type="submit" class="btn text-white submitalbum" style="background-color: #00008B">Submit</button>

                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <!-- <div class="col-lg-6 ">
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
            </div> -->
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

    // function previewImage() {
    //     const fileInput = document.getElementById('customFile');
    //     const imagePreview = document.getElementById('imagePreview');

    //     if (fileInput.files.length > 0) {
    //         const file = fileInput.files[0];
    //         const reader = new FileReader();

    //         reader.onload = function(event) {
    //             const img = document.createElement('img');
    //             img.src = event.target.result;
    //             img.style.maxWidth = '120%';
    //             img.style.maxHeight = '200px'; // Adjust the height as needed
    //             imagePreview.innerHTML = ''; // Clear previous preview
    //             imagePreview.appendChild(img);
    //         }

    //         reader.readAsDataURL(file);
    //     } else {
    //         imagePreview.innerHTML = ''; // Clear the preview if no file selected
    //     }
    // }
</script>
<script>
    $(document).ready(function() {
        
        addTestimonials();
    });

    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric'
            // month: 'long',
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    function addTestimonials() {
        $("#testimonial_form").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var form = this;
            $.ajax({
                type: "POST",
                url: `/Manage-Achivers/Add`,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
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

    // function testimonialList() {
    //     var listContainer = $('#card_list')
    //     listContainer.empty();
    //     $.ajax({
    //         type: "GET",
    //         url: `/Manage-Achivers/List/latest`,
    //         dataType: "json",
    //         success: function(response) {
    //             $.each(response.data, function(index, item) {
    //                 var card = `
    //             <div class="card mb-3" style="">
    //                 <div class="row no-gutters">
    //                   <div class="col-md-4">
    //                     <img src="{{ asset('/storage/${item.person_img}')}}" alt="" class="img-thumbnail">
    //                   </div>
    //                   <div class="col-md-8">
    //                     <div class="card-body">
    //                       <h5 class="card-title">${item.name}</h5>
    //                       <h6 class="card-text">${item.sort_desc_one}</h6>
    //                       <h6 class="card-text">${item.sort_desc_two}</h6>

    //                       <p class="card-text">${item.persone_note}</p>
    //                       <p class="card-text"><small class="text-muted">Created On ${formatDate(item.created_at)}</small></p>
    //                     </div>
    //                   </div>
    //                 </div>
    //             </div>
                
    //             `;
    //                 listContainer.append(card);
    //             });
    //         }
    //     });
    // }
</script>






@endsection

@endsection