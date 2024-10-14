<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Message</title>
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
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding: 20px 0;
        }

        .header img {
            max-width: 120px;
        }

        .content {
            padding: 20px;
            text-align: left;
        }

        .content h1 {
            font-size: 1.4em;
            color: #333;
        }

        .content p {
            font-size: 1em;
            color: #555;
        }

        .message {
            font-size: 1.2em;
            margin: 20px 0;
            color: #333;
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #666;
            padding: 10px;
            background: #f4f4f4;
            margin-top: 20px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #333;
            text-decoration: none;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://api.ncr-dswd.ph/logo/dswd-logov2.png" alt="DSWD Logo">
        </div>
        <div class="content">
            <div class="message">
            <?= $content ?>
            </div>

            <p>Thank you,<br>DSWD Team</p>
        </div>
        <div class="footer">
            <p>Sent by DSWD ● Check Our Blog ● @DSWD</p>
            <p>389 San Rafael Street, corner Legarda Street, Sampaloc, Manila, Philippines</p>
        </div>
    </div>
</body>

</html>
