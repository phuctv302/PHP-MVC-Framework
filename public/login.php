<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login - Base Account</title>

        <link rel="stylesheet" href="css/style.css" />
        <link
            rel="shortcut icon"
            href="https://share-gcdn.basecdn.net/apps/account.png"
            type="image/x-icon"
        />
    </head>
    <body>
        <div class="body">
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

                    <form action="" class="base-form auth-form">
                        <div class="form__group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Your email"
                            />
                        </div>

                        <div class="form__group">
                            <label for="password">Password</label>
                            <span class="forgot-password"
                                ><a href="#" class="text-blue"
                                    >Forget your password?</a
                                ></span
                            >
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Your password"
                            />
                        </div>
                        <div class="form__checkbox">
                            <input
                                type="checkbox"
                                name="save-auth"
                                id="save-auth"
                            />
                            <label for="save-auth" class="text--gray"
                                >Keep me logged in</label
                            >
                        </div>

                        <button class="btn btn--green btn--auth" type="submit">
                            Login to start working
                        </button>
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
        </div>
    </body>
</html>
