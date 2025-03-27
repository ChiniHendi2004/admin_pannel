<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tenant Login</h1>
        <div id="alert" class="alert d-none"></div>
        <form id="loginForm" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="client_slug" class="form-label">Client Slug</label>
                <input type="text" name="client_slug" id="client_slug" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div>
                <div class="text-center mt-3 row">
                    <a href="{{ route('tenant.registerForm') }}">Register </a>
                </div>

                <div class="text-center mt-3 row">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('login.post') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $("#alert").removeClass("d-none alert-danger").addClass("alert-success").text("Login successful!");
                        window.location.href = "/dashboard"; // Redirect on success
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors || ["Invalid credentials."];
                        $("#alert").removeClass("d-none alert-success").addClass("alert-danger").html(errors.join("<br>"));
                    }
                });
            });
        });
    </script>
</body>

</html>