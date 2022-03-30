<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php

use core\Application;
use core\MyCaptcha;

    $this->title = 'Login';

    $my_captcha = new MyCaptcha()
?>

<div class="body__auth">
    <div class="auth__container">
        <div class="auth__logo text--center">
            <img
                    class="text--center"
                    src="/public/img/logo.full.png"
                    alt="Base logo"
            />
        </div>
        <h1 class="auth__title text--center">Login</h1>
        <p class="auth__sub-title text--gray text--center">
            Welcome back. Login to start working
        </p>

        <form method="post" action="" class="base-form auth-form">
            <div class="form__group">
                <label>Your email</label>
                <input type="email" name="email" placeholder="Your email"
                       value="<?= $model->email ?>"
                       class="<?php echo $model->hasError("email") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("email") ?></div>
            </div>
            <div class="form__group">
                <label>Password</label>
                <span class="forgot-password">
                <a href="/forgot" class="text-blue">Forget your password?</a >
            </span>
                <input type="password" name="password" placeholder="Your password"
                       value="<?= $model->password ?>"
                       class="<?php echo $model->hasError("password") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("password") ?></div>
            </div>

            <?php
            // Captcha
            if (Application::$app->cookie->get('count') && Application::$app->cookie->get('count') >= 3) {
                echo "<div class='g-recaptcha' data-sitekey=" . $_ENV['PUBLIC_KEY'] . "></div>";
            }
            ?>

            <input name="submit" class="btn btn--green btn--auth" type="submit" value="Login to start working">
            <a href="/register" class="btn btn--green btn--auth" >Not having an account. Signup now!</a>
        </form>

        <div class="other-auth text--center">
            <p class="other-auth__title text--gray">
                Or, Login via single sign-on
            </p>

            <div class="other-auth__button">
                <a class="btn btn--gray text-blue"
                >Login with Google</a
                >
                <a class="btn btn--gray text-blue"
                >Login with Microsoft</a
                >
                <a class="btn btn--gray text-blue"
                >Login with SAML</a
                >
            </div>
        </div>

        <div class="guest-auth text--center">
            <a href="#" class="text-blue"
            >Login with Guest/Client access?</a
            >
        </div>
    </div>
</div>

<div class="body__background"></div>
