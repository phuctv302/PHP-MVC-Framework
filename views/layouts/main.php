<?php

use core\Application;

?>

    <!doctype html>
    <html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $this->title; ?> - Base Account</title>

    <link rel="stylesheet" href="/public/css/style.css" />

    <!-- Jquery cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link
            rel="shortcut icon"
            href="https://share-gcdn.basecdn.net/apps/account.png"
            type="image/x-icon"
    />

    <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.css"
            rel="stylesheet"
            media="all"
    />

    <style>
        .form-logout .logout-btn {
            border: none;
            background-color: transparent;
            background-repeat: no-repeat;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>
    <body>
        {{content}}

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