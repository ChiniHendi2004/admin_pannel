<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Register Tenant</h1>
        <div id="alert" class="alert d-none"></div>
        <form id="registerForm" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="client_slug" class="form-label">Client Slug</label>
                <input type="text" name="client_slug" id="client_slug" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="website_url" class="form-label">Website URL</label>
                <input type="text" name="website_url" id="website_url" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="number" name="employee_id" id="employee_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="academic_session" class="form-label">Academic Session</label>
                <input type="date" name="academic_session" id="academic_session" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" name="expiration_date" id="expiration_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            $("#registerForm").on("submit", function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('tenant.register') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        $("#alert").removeClass("d-none alert-danger").addClass("alert-success").text("Registration successful!");
                        $("#registerForm")[0].reset();
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors || ["An error occurred."];
                        $("#alert").removeClass("d-none alert-success").addClass("alert-danger").html(errors.join("<br>"));
                    }
                });
            });
        });
    </script>
</body>
</html>
