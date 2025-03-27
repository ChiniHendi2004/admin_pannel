@extends('layouts.app')
@section('pagetitle')
Update || Edit Content
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Update's Content</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Update's Content</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-10">
                <form action="" id="content-Form" enctype="multipart/form-data">
                    @csrf
                    <div class="card p-2 px-3">
                        <input type="hidden" name="update_id" id="update_id" value="{{ $update_id }}">
                        <input type="hidden" name="paragraph_order" id="paragraph_order" value="{{ $paragraph_order }}">
                        <label for="title">Title : <span class="mx-2" id="content_title"></span> </label>
                        <label for="Date">Date : <span class="mx-2" id="content_date"></span> </label>
                        <div class="d-flex my-2">
                            <label for="content-dropdown " style="margin-top: 5px">Paragraph No :
                                {{ $paragraph_order }}</label>
                        </div>
                        <div class="d-flex">
                            <div>
                                <label for="Date">Content type : </label>
                            </div>
                            <div></div>
                            <div class="d-flex ml-2">
                                <div class="custom-control custom-radio mr-2">
                                    <input class="custom-control-input" type="radio" id="customRadio1"
                                        name="content_type" value="text" checked>

                                    <label for="customRadio1" class="custom-control-label"
                                        style="cursor: pointer">Text</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2"
                                        name="content_type" value="image">
                                    <label for="customRadio2" class="custom-control-label"
                                        style="cursor: pointer">Image</label>
                                </div>
                            </div>
                        </div>
                        <div id="option1-fields" class="text">

                            <textarea name="paragraph_text" id="paragraph_text" cols="30" rows="10"></textarea>
                        </div>

                        <div class="imageinput" style="display: none;">
                            <div>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="content_img"
                                            id="customFile" accept="image/*">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <div id="imagePreview" class="my-2 w-25 h-50"></div>

                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn text-white"
                                style="background-color: #00008B">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
@section('scripts')
<script src="{{ asset('plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ asset('plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>


<script>
    // -- summernote initilize  --
    $(function() {
        // Summernote
        $('#paragraph_text').summernote({
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })
    })
</script>

{{-- image priview and label show  --}}
<script>
    $(document).ready(function() {
        // Listen for changes in the file input
        $('#customFile').change(function() {
            var fileInput = $(this)[0];
            var file = fileInput.files[0];
            var imagePreview = $('#imagePreview');

            if (file) {
                // Display the selected file's name in the custom-file-label
                $('.custom-file-label').text(file.name);

                // Check if the selected file is an image
                if (file.type.match('image.*')) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        // Display the image in the imagePreview div
                        var img = $('<img>').attr('src', e.target.result).addClass('img-fluid');
                        imagePreview.html(img);
                    };

                    reader.readAsDataURL(file);
                } else {
                    // If the selected file is not an image, clear the imagePreview
                    imagePreview.html('');
                }
            } else {
                // If no file is selected, reset the custom-file-label and clear the imagePreview
                $('.custom-file-label').text('Choose file');
                imagePreview.html('');
            }
        });
    });
</script>

{{-- radio button Wise input  --}}
<script>
    $('input[name="content_type"]').change(function() {
        var selectedValue = $('input[name="content_type"]:checked').val();

        // Hide/show fields based on the selected radio button
        if (selectedValue === "text") {
            $('.text').show();
            $('.imageinput').hide();
        } else if (selectedValue === "image") {
            $('.text').hide();
            $('.imageinput').show();
        }
    });
</script>

{{-- Title Data Fatch  --}}
<script>
    function content_title() {
        var update_id = $('#update_id').val();
        $.ajax({
            type: "GET",
            url: `{{ url('/Updates/Select/${update_id}') }}`,
            dataType: "json",
            success: function(response) {
                $('#content_title').html(response.updates.title)
                $('#content_date').html(formatDate(response.updates.event_date))
            }

        });
    }

    // Date formating 
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }
    // Get Title info 



    function getcontent() {
        var update_id = $('#update_id').val();
        var content_id = $('#paragraph_order').val();
        $.ajax({
            type: "GET",
            url: `{{ url('/Edit-Updates/Get/Content/${update_id}') }}`,
            data: {
                paragraph_order: content_id,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                // if (response.data.detail_type === 'text') {
                $('input[name="content_type"][value="text"]').prop('checked', true);
                $('.text').show();
                $('.imageinput').hide();
                $('#imagePreview').empty();
                $('#paragraph_text').summernote('code', response.data.paragraph_text);
                // } else if (response.data.detail_type === 'image') {
                //     $('input[name="content_type"][value="image"]').prop('checked', true);
                //     $('.text').hide(); // Hide the text input
                //     $('.imageinput').show(); // Show the image input
                //     $('#summernote').summernote('code', '');
                //     var img = $('<img>').attr('src',
                //         `{{ asset('/storage/${response.data.content_img}') }}`).addClass('img-fluid');
                //     $('#imagePreview').html(img);
                //     console.log(response.data.content_img);
                //     $('.custom-file-label').text(response.data.content_img);

                // }
            }
        });
    }


    $('#content-Form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        var summernoteValue = $('#paragraph_text').summernote('code');
        console.log("Summernote Content:", summernoteValue); // Debugging

        var selectedValue = $('input[name="content_type"]:checked').val();

        if (selectedValue === "text") {
            formData.append('paragraph_text', summernoteValue); // Append to FormData
            formData.delete('content_img');
            $('.custom-file-label').text('Choose File');
        } else if (selectedValue === "image") {
            formData.delete('paragraph_text');
        }

        $.ajax({
            type: "POST",
            url: `{{ url('/Updates/Add/Details') }}`,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Response:", response);
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                getcontent();
            },
            error: function(xhr, status, error) {
                console.error("Error:", xhr.responseText);
            }
        });
    });



    $(document).ready(function() {
        content_title()
        getcontent()
    });
</script>

<script></script>
@endsection
@endsection