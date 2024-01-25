<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* CSS Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
        }
        p {
            color: #777;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .otp-code {
            display: block;
            color: #333;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .note {
            text-align: center;
            color: #777;
            margin-top: 20px;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Reset Password</h1>
        <p>Hello,</p>
        <p>We received a request to reset your password. Please use the following OTP code to proceed:</p>
        <span class="otp-code">{{ $token }}</span>
        <p class="note">If you didn't request a password reset, no further action is required.</p>
    </div>
    <div class="footer">
        This email was sent by {{ config('app.name') }}.
    </div>
</body>

</html>
