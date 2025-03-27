<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Your Password</h1>
    <p>You requested a password reset. Please click the link below to reset your password:</p>
    <a href="{{ url('/reset-password/' . $token) }}">Reset Password</a>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
