<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; 
            font-family: Arial, sans-serif;
        }
        .message-container {
            text-align: center;
            background-color: #ff4d4d; 
            color: white; 
            padding: 20px 40px;
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            font-size: 2em; 
            font-weight: bold; 
        }
    </style>
</head>
<body>
    <div class="message-container">
        Access Restricted.
    </div>
</body>
</html>
