<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Server Error</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        
        .error-code {
            font-size: 15rem;
            color: #dc3545;
            font-weight: bold;
            line-height: 1;
            margin: 0;
            letter-spacing: 10px;
        }
        
        .error-title {
            font-size: 2.5rem;
            color: #495057;
            margin: 1rem 0;
            font-weight: normal;
        }
        
        .error-message {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .btn-home {
            display: inline-block;
            padding: 12px 30px;
            background-color: #495057;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-home:hover {
            background-color: #343a40;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Internal Server Error</h2>
        <p class="error-message">Sorry, something went wrong on our servers</p>
        <a href="{{ url('/') }}" class="btn-home">Go Back Home</a>
    </div>
</body>
</html> 