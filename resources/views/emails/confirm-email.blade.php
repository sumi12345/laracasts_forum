<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum</title>
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.js"></script>
</head>
<body>
<div class="container" id="app">

    <p><h1>LARAVEL 5</h1></p>

    <p><h2>{{ $user->name }}, Please Confirm you email</h2></p>

    <p>
        <a href="localhost:2221/confirm/{{ $user->confirm_token }}">
            <button class="btn btn-primary">现在就去</button>
        </a>
    </p>

</div>
</body>