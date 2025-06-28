<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Under Maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .maintenance-container {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .maintenance-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #f1c40f;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🛠️</div>
        <h1>Under Maintenance</h1>
        <p>We're currently performing scheduled maintenance to improve our services.</p>
        <p>Please check back later. We apologize for any inconvenience.</p>
        <p>Expected downtime: {{ isset($exception) ? $exception->getMessage() : '15 minutes' }}</p>
    </div>
</body>
</html>
