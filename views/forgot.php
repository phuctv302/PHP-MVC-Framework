<?php
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
            Enter your email to reset your password
        </p>

        <form action="" method="post" class="form base-form auth-form">
            <div class="form__group">
                <label>Your email</label>
                <input type="email" name="email" placeholder="Your email"
                       class="<?php echo $model->hasError("email") ? "is-invalid" : "" ?>"
                        value=<?php echo $model->email ?>>
                <div class="invalid-feedback"><?php echo $model->getFirstError("email") ?></div>
            </div>

            <button type="submit" class="btn btn--green btn--auth">Send token</button>
        </form>
    </div>
</div>

<div class="body__background"></div>

