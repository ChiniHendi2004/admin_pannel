@extends('layouts.app')
@section('pagetitle')
Update || Add Details
@endsection
@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h3 class="m-0" style="color: black;">Add Details</h3>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div id="responseMessage"></div>
        <div class="row px-2">
            <div class="col-lg-10">
                <form action="" id="content-Form" enctype="multipart/form-data">
                    @csrf
                    <div class="card px-3 py-3">
                        <input type="hidden" name="update_id" id="update_id" value="{{ $id }}">
                        <h3><label for="update_heading">Update :
                                <span class="mx-2" id="update_heading"></span>
                            </label></h3>
                        <label for="Date">Date : <span class="mx-2" id="content_date"></span> </label>

                        <div class="d-flex my-2">
                            <label for="paragraph-number" style="color:black; margin-top: 5px;">Paragraph No :</label>
                            <div class="w-25 mx-2">
                                <select class="form-control w-50" id="paragraph-number" name="paragraph_order">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="paragraph_text" class="form-label">Add Detail's</label>
                            <textarea class="form-control" id="summernote" name="paragraph_text"
                                placeholder="Add Details About Paragraph"
                                aria-placeholder="Enter Content" required></textarea>
                            <small id="error-roleDesc" class="text-danger"></small>
                        </div>

                        <div>
                            <button type="submit" class="btn text-white btn-primary"
                                style="background-color: #00008B; width: 10rem;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    $(function() {
        // Summernote
        $('#summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })
    })

    function getupdateInfo() {
        var updateId = $('#update_id').val();

        $.ajax({
            type: "GET",
            url: `/Updates/Select/${updateId}`,
            dataType: "json",
            success: function(response) {
                $('#update_heading').text(response.updates.title);
                $('#content_date').text(formatDate(response.updates.event_date));
            },
            error: function(xhr, status, error) {
                console.error("Error fetching update details:", xhr.responseText);
            }
        });
    }

    function fetchParagraphContent() {
        var updateId = $('#update_id').val();
        var paragraphOrder = $('#paragraph-number').val(); // Get selected paragraph order

        $.ajax({
            type: "GET",
            url: `/Edit-Updates/Get/Content/${updateId}`, // Correct endpoint
            data: {
                paragraph_order: paragraphOrder
            }, // Pass paragraph_order
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $('#summernote').summernote('code', response.data.paragraph_text || ''); // Set paragraph content
                } else {
                    $('#summernote').summernote('code', ''); // Clear editor if no content found
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching paragraph content:", xhr.responseText);
            }
        });
    }

    $(document).ready(function() {
        getupdateInfo();
        fetchParagraphContent();

        $('#paragraph-number').change(function() {
            fetchParagraphContent();
        });

        $('#content-Form').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('paragraph_order', $('#paragraph-number').val()); // Include paragraph_order
            formData.append('paragraph_text', $('#summernote').summernote('code')); // Get Summernote content

            $.ajax({
                type: "POST",
                url: `/Updates/Add/Details`,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    fetchParagraphContent(); // Reload the current paragraph after update
                },
                error: function(xhr, status, error) {
                    console.error("Error submitting form:", xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
@endsection