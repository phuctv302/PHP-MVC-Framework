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

    <link rel="stylesheet" href="public/css/style.css" />
    <link
            rel="shortcut icon"
            href="https://share-gcdn.basecdn.net/apps/account.png"
            type="image/x-icon"
    />
</head>
    <body>
<!--    <nav class="navbar navbar-expand-lg navbar-light bg-light">-->
<!--        <div class="container-fluid">-->
<!--            <a class="navbar-brand" href="#">Navbar</a>-->
<!--            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"-->
<!--                    data-bs-target="#navbarSupportedContent"-->
<!--                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                <span class="navbar-toggler-icon"></span>-->
<!--            </button>-->
<!--            <div class="collapse navbar-collapse" id="navbarSupportedContent">-->
<!--                <ul class="navbar-nav me-auto mb-2 mb-lg-0">-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link active" aria-current="page" href="/">Home</a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="/contact">Contact</a>-->
<!--                    </li>-->
<!--                </ul>-->
<!---->
<!--                --><?php //if (Application::isGuest()): ?>
<!--                    <ul class="navbar-nav ml-auto mb-2 mb-lg-0">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link active" aria-current="page" href="/login">Login-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="/register">Register</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                --><?php //else: ?>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="/profile">Profile</a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link"-->
<!--                           href="/logout">Welcome --><?php //echo Application::$app->user->getDisplayName() ?>
<!--                            (Log out)-->
<!--                        </a>-->
<!--                    </li>-->
<!--                --><?php //endif ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </nav>-->

    <div class="content">
        <?php if (Application::$app->session->getFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Application::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>
        {{content}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

    </body>
    </html>

<?php
