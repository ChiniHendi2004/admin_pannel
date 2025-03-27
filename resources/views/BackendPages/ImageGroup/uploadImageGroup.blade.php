@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Add IMage In Items</div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Select Group:</label>
                        <select id="groupSelect" class="form-control">
                            <option value="">-- Select Group --</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Select Item:</label>
                        <select id="itemSelect" class="form-control">
                            <option value="">-- Select Item --</option>
                        </select>
                    </div>

                    <form id="uploadForm">
                        @csrf
                        <input type="hidden" name="group" id="group">
                        <input type="hidden" name="item_id" id="item_id">

                        <div class="form-group mb-3">
                            <label class="form-label">Image title:</label>
                            <input type="text" name="title" id="title" placeholder="Enter Image title" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Image URL:</label>
                            <input type="text" name="image_url" id="image_url" placeholder="Enter Image URL" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Short Description:</label>
                            <input type="text" name="short_description" id="short_description" placeholder="Enter Short Description" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-4">Stored Images</h2>
    <table id="imagesTable" class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Group Name</th>
                <th>Item Name</th>
                <th>Image</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        loadImages();

        $('#groupSelect').change(function() {
            var groupType = $(this).val();
            $('#group').val(groupType);
            $('#itemSelect').html('<option value="">-- Select Item --</option>');

            if (groupType) {
                $.ajax({
                    url: `/get-items/${groupType}`,
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(index, item) {
                            $('#itemSelect').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            }
        });

        $('#itemSelect').change(function() {
            $('#item_id').val($(this).val());
        });

        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            var formData = {
                _token: '{{ csrf_token() }}',
                group: $('#group').val(),
                item_id: $('#item_id').val(),
                title: $('#title').val(),
                image_url: $('#image_url').val(),
                short_description: $('#short_description').val()
            };

            $.ajax({
                url: "/upload-image",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                    loadImages();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Failed to submit data. Check console for details.");
                }
            });
        });

        function loadImages() {
            $.ajax({
                url: "/get-images",
                type: "GET",
                success: function(response) {
                    $("#imagesTable tbody").empty();
                    response.data.forEach((item, index) => {
                        $("#imagesTable tbody").append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.group_name}</td>
                                <td>${item.item_name}</td>
                                <td><img src="${item.image_url}" width="50"></td>
                                <td>
                                    <select class="form-select select-status" data-id="${item.id}">
                                        <option value="1" ${item.status == 1 ? "selected" : ""}>Active</option>
                                        <option value="0" ${item.status == 0 ? "selected" : ""}>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-image" data-id="${item.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }

        $(document).on("click", ".delete-image", function() {
            var imageId = $(this).data("id");

            if (confirm("Are you sure you want to delete this image?")) {
                $.ajax({
                    url: `/delete-image/${imageId}`,
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        loadImages();
                    }
                });
            }
        });

        $(document).on("change", ".select-status", function() {
            var imageId = $(this).data("id");
            var newStatus = $(this).val();

            $.ajax({
                url: `/update-image-status/${imageId}`,
                type: "PUT",
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function() {
                    loadImages();
                }
            });
        });

        
    });
</script>
@endsection