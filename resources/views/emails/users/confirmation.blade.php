<!DOCTYPE html>
<html>
<head>
    <title>User Account Confirmation</title>
</head>
<body>

    <h2>Hi {{ $user->first_name }},</h2>

    <br>

    <p>Please confirm your account by clicking on the link below:</p>
    <p><a href="{{ $confirmation_url }}">{{ $confirmation_url }}</a></p>

    <br><br>

    <p>Regards,<br>LaraGist Team</p>

</body>
</html>
