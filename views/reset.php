<?php
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
            <div class="form__group">
                <label>New password</label>
                <input type="password" name="password" placeholder="New password"
                       class="<?php echo $model->hasError("password") ? "is-invalid" : "" ?>"
                <div class="invalid-feedback"><?php echo $model->getFirstError("password") ?></div>
            </div>
            <div class="form__group">
                <label>Confirm new password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password"
                       class="<?php echo $model->hasError("confirm_password") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("confirm_password") ?></div>
            </div>

            <button type="submit" class="btn btn--green btn--auth">Reset password</button>
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
