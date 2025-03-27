@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Organization</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add Organization</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-4">
        <div id="responseMessage"></div>

        <form action="" id="organisationForm" class="card" enctype="multipart/form-data">
            @csrf
            <div class="row p-3">
                <div class="col-lg-6 p-3">
                    <h5>Organization information</h5>
                    <div class="mb-3">
                        <label for="info_type" class="form-label">Information type</label>
                        <select class="form-select" id="info_type" name="info_type">
                            <option value="" selected disabled>Select Information Type</option>
                            <option value="1">Organization Information</option>
                            <option value="2">Adminstrative Office Information</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="mobile_no" class="form-label">Mobile No</label>
                        <input type="number" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter Mobile No" value="{{ old('mobile_no') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="phone_no" class="form-label">Phone No</label>
                        <input type="number" class="form-control" id="phone_no" name="phone_no" placeholder="Enter Phone No" value="{{ old('phone_no') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="fax_no" class="form-label">Fax No</label>
                        <input type="number" class="form-control" id="fax_no" name="fax_no" placeholder="Enter Fax No" value="{{ old('fax_no') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="text" class="form-control" id="logo" name="logo" placeholder="Enter logo url" value="{{ old('logo') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="footer_logo" class="form-label">Footer Logo</label>
                        <input type="text" class="form-control" id="footer_logo" name="footer_logo" placeholder="Enter footer logo url" value="{{ old('footer_logo') }}" />
                    </div>
                    <!-- <div class="mb-3">
                        <label for="customFile" class="form-label">Select Logo</label>
                        <input type="file" class="form-control" id="customFile" name="logo" onchange="updateLabel(); previewImage();" accept="image/*">
                        <div id="imagePreview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label for="footerfile" class="form-label">Select Footer Logo</label>
                        <input type="file" class="form-control" id="footerfile" name="footer_logo" onchange="updateLabelText(); ShowImage();" accept="image/*">
                        <div id="imageview" class="mt-2"></div>
                    </div> -->
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook Link</label>
                        <input type="url" class="form-control" id="facebook" name="facebook" placeholder="Enter Facebook Link" value="{{ old('facebook') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="instagram" class="form-label">Instagram Link</label>
                        <input type="url" class="form-control" id="instagram" name="instagram" placeholder="Enter Instagram Link" value="{{ old('instagram') }}" />
                    </div>

                    <div class="mb-3">
                        <label for="org_map" class="form-label">Org_map Link</label>
                        <input type="url" class="form-control" id="org_map" name="org_map" placeholder="Enter org_map Link" value="{{ old('org_map') }}" />
                    </div>
                </div>

                <div class="col-lg-6 p-3">
                    <h5>Address information</h5>
                    <div class="mb-3">
                        <label for="address_one" class="form-label">Address 1</label>
                        <input type="text" class="form-control" id="address_one" name="address_one" placeholder="Enter Address 1" value="{{ old('address_one') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="address_two" class="form-label">Address 2</label>
                        <input type="text" class="form-control" id="address_two" name="address_two" placeholder="Enter Address 2" value="{{ old('address_two') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" name="state">
                            <option selected disabled value="">Select Your State</option>
                            <option selected disabled value="">Select Your State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">District</label>
                        <input type="text" class="form-control" id="district" name="district" placeholder="Enter District" value="{{ old('district') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City Name</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter City Name" value="{{ old('city') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="pin" class="form-label">Pin Code</label>
                        <input type="text" class="form-control" id="pin" name="pin" placeholder="Enter Pin Code" value="{{ old('pin') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="youtube" class="form-label">Youtube Link</label>
                        <input type="url" class="form-control" id="youtube" name="youtube" placeholder="Enter Youtube Link" value="{{ old('youtube') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">Whatsapp Link</label>
                        <input type="url" class="form-control" id="whatsapp" name="whatsapp" placeholder="Enter whatsapp Link" value="{{ old('whatsapp') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="google" class="form-label">Google Link</label>
                        <input type="url" class="form-control" id="google" name="google" placeholder="Enter Google Link" value="{{ old('google') }}" />
                    </div>
                    <div class="mb-3">
                        <label for="linkedin" class="form-label">LinkedIn Link</label>
                        <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="Enter LinkedIn Link" value="{{ old('linkedin') }}" />
                    </div>

                    <div class="col-12 mb-3">
                        <label for="website_url" class="form-label">Website Link</label>
                        <input type="url" class="form-control" id="website_url" name="website_url" placeholder="Enter Website Link" value="{{ old('website_url') }}" />
                    </div>
                </div>

                <div class="mx-3">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
    </div>
</div>

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle form submission
        $('#organisationForm').submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            console.log(...formData); // For debugging

            // Perform AJAX request
            $.ajax({
                type: 'POST',
                url: '/organization/store', // Adjust the URL to your route
                data: formData,
                processData: false, // Important when using FormData
                contentType: false, // Important when using FormData
                success: function(response) {
                    // Handle success response
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    // Optionally, reset the form
                    $('#organisationForm')[0].reset();
                },
                error: function(xhr) {
                    // Handle error response
                    if (xhr.status === 422) {
                        // Validation error
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '<div class="alert alert-danger"><ul>';
                        $.each(errors, function(key, value) {
                            errorMessages += '<li>' + value[0] + '</li>';
                        });
                        errorMessages += '</ul></div>';
                        $('#responseMessage').html(errorMessages);
                    } else {
                        // Other errors
                        $('#responseMessage').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    }
                }
            });
        });
    });
</script>

@endsection

@endsection