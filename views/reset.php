<?php

/** @var $model \core\Model */

$this->title = 'Reset your password';
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
        <h1 class="auth__title text--center">Reset password</h1>
        <p class="auth__sub-title text--gray text--center">
            Please remember your password!
        </p>

        <form action="" method="post" class="form base-form auth-form">
            <input type="hidden" value="">

            <?php echo \inputs\InputField::render("New password", "password", "password", "New password", $model) ?>
            <?php echo \inputs\InputField::render("Confirm new password", "password", "confirm_password", "Confirm new password", $model) ?>

            <button type="submit" class="btn btn--green btn--auth">Reset password</button>
        </form>
    </div>
    <div class="body__background"></div>
</div>
