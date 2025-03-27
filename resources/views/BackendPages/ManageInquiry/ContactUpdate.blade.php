@extends('layouts.app')

@section('pagetitle')
Contact || Update
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-0" style="color: black;">Edit Contact</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Contact</li>
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

            <!-- Left Column: Edit Form -->
            <div class="col-lg-6">
                <div class="card-header text-center fw-bold" style="color: black;">View Details</div>
                <div class="mt-4" id="card-list"></div>
            </div>
           

            <!-- Right Column: View Details -->
            <div class="col-lg-6">
                <div class="card-header text-center fw-bold" style="color: black;">Edit Contact Details</div>
                <div class="card mt-4 p-3">
                    <form id="contact_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="testi_id" id="testi_id" value="{{$id}}">
                        <div class="mb-3">
                            <label for="visitor_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="visitor_name" placeholder="Enter Name" name="visitor_name" value="{{ old('visitor_name') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="visitor_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="visitor_email" placeholder="Enter email" name="visitor_email" value="{{ old('visitor_email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="visitor_phone" class="form-label">Number</label>
                                    <input type="number" class="form-control" id="visitor_phone" name="visitor_phone" placeholder="Enter Your Number" required value="{{ old('visitor_phone') }}">
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="complaint_status" class="form-label">Complaint Status</label>
                            <textarea class="form-control" id="complaint_status" name="complaint_status" placeholder="Enter Complaint" required>{{ old('complaint_status') }}</textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" placeholder="Write About Your Achievement" rows="4" required>{{ old('message') }}</textarea>

                        </div>
                        <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 20rem;">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        contactDetails();
        addContact();
    });

    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    function addContact() {
        $("#contact_form").submit(function(event) {
            event.preventDefault();
            var testi_id = $('#testi_id').val();
            var formData = new FormData(this);
            formData.append("_method", "PUT");

            $.ajax({
                type: "POST",
                url: `/Manage-Contact/Update/${testi_id}`,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    $("#contact_form")[0].reset();
                    contactDetails();
                },
                error: function(error) {
                    console.error(error);
                },
            });
        });
    }

    function contactDetails() {
        var Container = $('#card-list');
        Container.empty();
        var testi_id = $('#testi_id').val();

        $.ajax({
            type: "GET",
            url: `/Manage-Contact/Select/${testi_id}`,
            dataType: "json",
            success: function(response) {
                $('#visitor_name').val(response.data.visitor_name); 
                $('#visitor_email').val(response.data.visitor_email);
                $('#visitor_phone').val(response.data.visitor_phone);
                $('#message').val(response.data.message);
                $('#complaint_status').val(response.data.complaint_status);

                var card = `
                    <div class="card px-3 pb-3">
                            <h5 class="card-title">${response.data.visitor_name}</h5>
                            <p class="card-text">visitor_email: ${response.data.visitor_email}</p>
                            <p class="card-text">Phone: ${response.data.visitor_phone}</p>
                            <p class="card-text">Message: ${response.data.message}</p>
                            <p class="card-text">Complaint Status: ${response.data.complaint_status}</p>
                            <p class="card-text"><small class="text-muted">Created On ${formatDate(response.data.created_at)}</small></p>
                    </div>
                `;
                Container.append(card);
            }
        });
    }
</script>
@endsection

@endsection