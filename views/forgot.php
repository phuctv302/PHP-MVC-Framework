<?php

/** @var $model \core\Model */

$this->title = 'Forgot password';
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
        <h1 class="auth__title text--center">Forgot password</h1>
        <p class="auth__sub-title text--gray text--center">
            Enter your email to reset your
        </p>

        <form action="/forgot" method="post" class="form base-form auth-form">

            <?php echo \inputs\InputField::render("Your email", "email", "email", "Your email", $model) ?>

            <button type="submit" class="btn btn--green btn--auth">Send token</button>
        </form>
    </div>
</div>

<div class="body__background"></div>

