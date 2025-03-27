<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    @if(session('status'))
        <p style="color: green;">{{ session('status') }}</p>
    @endif
    <form action="{{ route('password.email') }}" method="POST">
    @csrf
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Send Password Reset Link</button>
</form>

</body>
</html>
