<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="public/css/style.css"/>
    <link
            rel="shortcut icon"
            href="https://share-gcdn.basecdn.net/apps/account.png"
            type="image/x-icon"
    />

    <title><?php use core\Application;

        echo $this->title ?> - Base Account</title>
</head>
<body>
<div class="body">
    {{content}}
</div>

<script src="/public/js/script.js"></script>
<script>
    const success_mes = formatMessage("\"<?php echo Application::$app->session->getFlash('success') ?>\"");
    const error_mes = formatMessage("\"<?php echo Application::$app->session->getFlash('error') ?>\"");

    if (success_mes){
        showAlert('success', success_mes);
    } else if (error_mes){
        showAlert('error', error_mes);
    }
</script>
</body>
</html>
