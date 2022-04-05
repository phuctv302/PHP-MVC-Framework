<?php
    $this->title = 'Signup'
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
        <h1 class="auth__title text--center">Signup</h1>
        <p class="auth__sub-title text--gray text--center">
            Welcome. Signup to start working
        </p>

        <form action="/register" method="post" class="base-form auth-form">
            <div class="form__group">
                <label>First name</label>
                <input type="text" name="firstname" placeholder="Your first name"
                       value="<?= $model->firstname ?>"
                       required
                       class="<?php echo $model->hasError("firstname") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("firstname") ?></div>
            </div>
            <div class="form__group">
                <label>Last name</label>
                <input type="text" name="lastname" placeholder="Your last name"
                       required
                       value="<?= $model->lastname ?>"
                       class="<?php echo $model->hasError("lastname") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("lastname") ?></div>
            </div>
            <div class="form__group">
                <label>Your email</label>
                <input type="email" name="email" placeholder="Your email"
                       required
                       value="<?= $model->email ?>"
                       class="<?php echo $model->hasError("email") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("email") ?></div>
            </div>
            <div class="form__group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username"
                       required
                       value="<?= $model->username ?>"
                       class="<?php echo $model->hasError("username") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("username") ?></div>
            </div>
            <div class="form__group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Your password"
                       required
                       value="<?= $model->password ?>"
                       class="<?php echo $model->hasError("password") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("password") ?></div>
            </div>
            <div class="form__group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm password"
                       required
                       value="<?php echo $model->confirm_password ?>"
                       class="<?php echo $model->hasError("confirm_password") ? "is-invalid" : "" ?>">
                <div class="invalid-feedback"><?php echo $model->getFirstError("confirm_password") ?></div>
            </div>

            <button type="submit" class="btn btn--green btn--auth">Sign up your new account</button>
            <a href="/login" class="text--center text--blue" >Having an account. Login now!</a>
        </form>

        <div class="other-auth text--center">
            <p class="other-auth__title text--gray">
                Or, Login via single sign-on
            </p>

            <div class="other-auth__button">
                <a class="btn btn--gray text--blue"
                >Login with Google</a
                >
                <a class="btn btn--gray text--blue"
                >Login with Microsoft</a
                >
                <a class="btn btn--gray text--blue"
                >Login with SAML</a
                >
            </div>
        </div>

        <div class="guest-auth text--center">
            <a href="#" class="text--blue"
            >Login with Guest/Client access?</a
            >
        </div>
    </div>
</div>

<div class="body__background"></div>
