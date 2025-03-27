@extends('layouts.app')
@section('pagetitle')
News || Update
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-0" style="color: black;">Update News</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update News</li>
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
            <div class="col-lg-6">
                
                    <div class="card-header text-center fw-bold" style="color: black;">View News Details</div>
                    <div id="card-list" class=" mt-4">
                        <!-- Dynamic Content Will Be Loaded Here -->
                    </div>
                
            </div>
            <div class="col-lg-6">
                
                    <div class="card-header text-center fw-bold" style="color: black;">Edit News Details</div>
                    <div class="card p-3 mt-4">
                        <!-- Form start -->
                        <form id="News_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="testi_id" id="testi_id" value="{{$id}}">
                            <div class="row mb-2">
                                <div class="col-md-12 mb-3">
                                    <label for="heading" class="form-label">Heading</label>
                                    <input type="text" class="form-control" id="heading" placeholder="Enter Name" name="heading" value="{{ old('heading') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="publication_date" class="form-label">Publish Date</label>
                                    <input type="date" class="form-control" id="publication_date" name="publication_date" value="{{ old('publication_date') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="heading_url" class="form-label">Heading URL</label>
                                    <input type="url" class="form-control" id="heading_url" name="heading_url" placeholder="Enter URL" required value="{{ old('heading_url') }}">
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="body" class="form-label">Message</label>
                                    <textarea class="form-control" id="body" name="body" placeholder="Enter Message" required>{{ old('body') }}</textarea>
                                    <small id="error-roleDesc" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 20rem;">Submit</button>
                            </div>
                        </form>
                    </div>
                
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function () {
        NewsDetails();
        addNews();
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

    function addNews() {
        $("#News_form").submit(function (event) {
            event.preventDefault();
            var testi_id = $('#testi_id').val();
            var formData = new FormData(this);
            formData.append("_method", "PUT");
            
            $.ajax({
                type: "POST",
                url: `/Scrooling-News/Update/${testi_id}`,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    $("#News_form")[0].reset();
                    NewsDetails();
                },
                error: function (error) {
                    console.error(error);
                },
            });
        });
    }

    function NewsDetails() {
        var Container = $('#card-list');
        Container.empty();
        var testi_id = $('#testi_id').val();
        
        $.ajax({
            type: "GET",
            url: `/Scrooling-News/get/${testi_id}`,
            dataType: "json",
            success: function (response) {
                $('#heading').val(response.data.heading);
                $('#heading_url').val(response.data.heading_url);
                $('#publication_date').val(response.data.publication_date);
                $('#body').val(response.data.body);

                var card = `
                <div class="card mb-3">
                    <div class="card-body"> 
                        <h5 class="card-title">${response.data.heading}</h5>
                        <p>Heading URL: <a href="${response.data.heading_url}" class="card-text text-primary" target="_blank">${response.data.heading_url}</a></p>
                        <p class="card-text">Message: ${response.data.body}</p>
                        <p class="card-text">Publish Date: ${response.data.publication_date}</p>
                        <p class="card-text"><small class="text-muted">Created On ${formatDate(response.data.created_at)}</small></p>
                    </div>
                </div>`;
                Container.append(card);
            }
        });
    }
</script>
@endsection

@endsection