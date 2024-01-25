<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email OTP</title>
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
            border-radius: 10px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
        }
        .title {
            color: #333;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        .otp-container {
            text-align: center;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
        }
        .otp-code {
            color: #333;
            font-size: 36px;
            font-weight: bold;
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
        <div class="logo">
            <img src="{{ asset('path/to/dob_test_logo.png') }}" alt="dob_test Logo">
        </div>
        <h1 class="title">Email OTP</h1>
        <div class="otp-container">
            <span class="otp-code">{{ $otp }}</span>
        </div>
        <p class="note">Please use this OTP to verify your email.</p>
    </div>
    <div class="footer">
        This email was sent by {{ config('app.name') }}.
    </div>
</body>

</html>
