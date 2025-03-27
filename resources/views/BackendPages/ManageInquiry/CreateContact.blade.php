@extends('layouts.app')

@section('pagetitle')
Contact || Add
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h3 class="mb-0" style="color: black;">Add Contacts</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact</li>
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


                <div class="card p-3">
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="contact_form" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div>
                                    <label for="visitor_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="visitor_name" placeholder="Enter Name" name="visitor_name" value="{{ old('visitor_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label for="visitor_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="visitor_email" placeholder="Enter email" name="visitor_email" value="{{ old('visitor_email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label for="visitor_phone" class="form-label">Phone Number</label>
                                    <input type="number" class="form-control" id="visitor_phone" name="visitor_phone" placeholder="Enter Your Number" value="{{ old('visitor_phone') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label for="complaint_status" class="form-label">Complaint</label>
                                    <textarea class="form-control" id="complaint_status" name="complaint_status" rows="2" placeholder="Enter Complaint Status" required>{{ old('complaint_status') }}</textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="2" placeholder="Write About Your Achievements" required>{{ old('message') }}</textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 20rem;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


 
@section('scripts')

<script>
    $(document).ready(function() {
        $('#contact_form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Serialize form data
            var formData = new FormData(this);

            $.ajax({
                url: `{{ url('Create/Contact/Add') }}`, // Directly use the route URL
                type: 'POST',
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                beforeSend: function() {
                    // Add any pre-submit loading logic here
                },
                // success: function(response) {
                //     // Handle success
                //     alert('Form submitted successfully!');
                //     console.log(response);
                // },
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message +
                        '</div>');
                    form.reset();
                },
                error: function(xhr) {
                    // Handle error
                    alert('An error occurred. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>








@endsection

@endsection