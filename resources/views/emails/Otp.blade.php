<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header,
        .footer {
            text-align: center;
            background: #f4f4f4;
            padding: 10px;
        }

        .header img {
            max-width: 100px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .otp {
            font-size: 1.5em;
            margin: 20px 0;
        }

        .footer {
            font-size: 0.9em;
            color: #333;
        }

        .footer a {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://api.ncr-dswd.ph/logo/dswd-logov2.png" alt="dswd-logo">
        </div>
        <div class="content">
            <p> kindly enter the OTP below:</p>
            <div class="otp">
                {{ $otp }}
            </div>
        </div>
        <hr>
        <div class="footer">
            <p>Sent by DSWD ● Check Our Blog ● @DSWD</p>
            <p>389 San Rafael Street corner Legarda Street,Sampaloc, Manila Philippines</p>
        </div>
    </div>
</body>

</html>
