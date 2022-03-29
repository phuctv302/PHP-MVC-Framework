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

        <?php $form = \core\form\Form::begin("", "post", "base-form auth-form") ?>

            <div class="form__group">
                <?php echo $form->field($model, 'firstname') ?>
            </div>

            <div class="form__group">
                <?php echo $form->field($model, 'lastname') ?>
            </div>

            <div class="form__group">
                <?php echo $form->field($model, 'email') ?>
            </div>

            <div class="form__group">
                <?php echo $form->field($model, 'username') ?>
            </div>

            <div class="form__group">
                <?php echo $form->field($model, 'password')->passwordField() ?>
            </div>

            <div class="form__group">
                <?php echo $form->field($model, 'confirm_password')->passwordField() ?>
            </div>


            <button type="submit" class="btn btn--green btn--auth">Sign up your new account</button>
        <?php \core\form\Form::end() ?>

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
