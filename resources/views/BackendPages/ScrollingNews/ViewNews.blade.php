@extends('layouts.app')
@section('pagetitle')
View || News
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3 class="fw-bold mb-0" style="color: black;">News</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->
    <div class="content px-2">
        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-6">
                <input type="hidden" id="testi_id" value="{{$id}}">
                <div class="card-header text-center fw-bold" style="color: black;">View News Details</div>
                <div id="card-list" class=" mt-4">
                    <!-- Dynamic Content Will Be Loaded Here -->
                </div>

            </div>
        </div>
    </div>
    <!-- Main content -->

</div>

@section('scripts')
<script>
    $(document).ready(function() {
        NewsDetails();
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

    function NewsDetails() {
        var Container = $('#card-list');
        Container.empty();
        var testi_id = $('#testi_id').val();

        $.ajax({
            type: "GET",
            url: `/Scrooling-News/get/${testi_id}`,
            dataType: "json",
            success: function(response) {
                console.log(response);
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