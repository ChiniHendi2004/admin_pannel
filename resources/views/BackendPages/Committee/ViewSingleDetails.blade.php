@extends('layouts.app')
@section('pagecommittee_name')

Committee Single Details
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h1 class="m-0" style="color: black;">Single Committee Details</h1>
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Single Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>
        <div class="row px-2">
            <div class="col-lg-10">
                <form action="" id="detail_Form" enctype="multipart/form-data">
                    @csrf
                    <div class="card py-3 px-3">
                        <input type="hidden" name="committee_id" id="committee_id" value="{{ $id }}">
                        <input type="hidden" name="paragraph_order" id="paragraph_id" value="{{ $paragraphid }}">
                        <h3><label for="committee_name">Title : <span class="mx-2" id="committee_name"></span> </label></h3>
                        <strong><label for="short_description">Short Description : <span class="mx-2" id="short_description"></span> </label></strong>
                        <div class="d-flex my-2">
                            <label for="content-dropdown" style="margin-top: 3px; color: black;">Paragraph No :
                                {{ $paragraphid }}</label>
                        </div>

                        <div class="text mb-3">
                            <textarea name="paragraph_text" id="summernote" cols="30" rows="10"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 20rem;">Submit</button>
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
        $('#summernote').summernote({
            height: 300, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true,
        })
    })
</script>


{{-- Class Data Fatch  --}}
<script>
    function committee_name() {
        var committee_id = $('#committee_id').val();
        $.ajax({
            type: "GET",
            url: `/Committee/Select/${committee_id}`,
            dataType: "json",
            success: function(response) {
                console.log(response);
                $('#committee_name').html(response.data.committee_name)
                $('#short_description').html(response.data.short_description)
            }

        });
    }

    // Date formating 
    // function formatDate(dateString) {
    //     const options = {
    //         year: 'numeric',
    //         month: 'long',
    //         day: 'numeric'
    //     };
    //     const date = new Date(dateString);
    //     return date.toLocaleDateString(undefined, options);
    // }
    // Get Title info 



    function getdetail() {
        var committee_id = $('#committee_id').val();
        var paragraph_id = $('#paragraph_id').val();
        $.ajax({
            type: "GET",
            url: `/Committee/Get/Details/${committee_id}`,
            data: {
                paragraph_order: paragraph_id,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                $('#summernote').summernote('code', response.data.paragraph_text);
            }
        });
    }


    $('#detail_Form').on('submit', function(e) {

        e.preventDefault();
        var formData = new FormData(this);
        var summernoteValue = $('#summernote').summernote('code', "");

        $.ajax({
            type: "POST",
            url: `/Committee/Add/Details`,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#responseMessage').html('<div class="alert alert-success">' +
                    response.message + '</div>');
                getdetail()
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });


    $(document).ready(function() {
        committee_name()
        getdetail()
    });
</script>

<script></script>
@endsection
@endsection