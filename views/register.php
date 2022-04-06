<?php

    use inputs\InputField;

    $this->title = 'Signup';

    $input_field = new InputField()
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

            <?php echo $input_field->render("First name", "text", "firstname", "Your first name", $model) ?>
            <?php echo $input_field->render("Last name", "text", "lastname", "Your last name", $model) ?>
            <?php echo $input_field->render("Email", "email", "email", "Your email", $model) ?>
            <?php echo $input_field->render("Username", "text", "username", "Your username", $model) ?>
            <?php echo $input_field->render("Password", "password", "password", "Your password", $model) ?>
            <?php echo $input_field->render("Confirm password", "password", "confirm_password", "Your confirm password", $model) ?>

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
