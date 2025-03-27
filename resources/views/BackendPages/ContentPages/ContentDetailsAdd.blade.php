@extends('layouts.app')
@section('pagetitle')
Content || Add Details
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
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
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
                        <input type="hidden" name="content_id" id="content_id" value="{{ $id }}">
                        <h3><label for="content_heading">Content :
                                <span class="mx-2" id="content_heading"></span>
                            </label></h3>
                        <strong><label for="content_description">Description :
                                <span class="mx-2" id="content_description"></span>
                            </label></strong>

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
    $(function() {

        // Summernote
        $('#summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })


    })

    function getFacilityInfo() {
        var contentId = $('#content_id').val();

        $.ajax({
            type: "GET",
            url: `/Content/Get/Title/${contentId}`,
            dataType: "json",
            success: function(response) {
                $('#content_heading').html(response.data.title);
                $('#content_description').html(response.data.short_description);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching department details:", xhr.responseText);
            }
        });
    }

    function fetchParagraphContent() {
        var contentId = $('#content_id').val();
        var paragraphOrder = $('#paragraph-number').val(); // Get selected paragraph order

        $.ajax({
            type: "GET",
            url: `/Edit-Content/Get/Details/List/${contentId}`, // Correct endpoint
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
        getFacilityInfo();
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
                url: `/Edit-Content/Add/Details`,
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