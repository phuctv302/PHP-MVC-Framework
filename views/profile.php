<?php
/** @var $this \core\View
 * @var $model \core\Model;
 * @var $user \core\UserModel
 */

use core\Session;

$this->title = $user->getDisplayName(); // this ~ view instance
?>

<div class="user-view">
    <!-- Left navbar -->
    <nav class="user-view__left-menu">
        <ul class="left-nav">
            <li class="left-nav__user-image">
                <img src="/public/img/users/<?php echo $user->photo ?>" alt="User image"/>
            </li>
            <li class="left-nav__el left-nav__el--active">
                <i class="fas fa-user-circle"></i>
                <p class="el__label">Account</p>
            </li>
            <li class="left-nav__el left-nav__el">
                <i class="fas fa-user-friends"></i>
                <p class="el__label">Members</p>
            </li>
            <li class="left-nav__el left-nav__el">
                <i class="fas fa-code-branch"></i>
                <p class="el__label">Groups</p>
            </li>
            <li class="left-nav__el left-nav__el">
                <i class="far fa-square"></i>
                <p class="el__label">Guests</p>
            </li>
            <li class="left-nav__el left-nav__el">
                <i class="far fa-bookmark"></i>
                <p class="el__label">Application</p>
            </li>
        </ul>

        <form class="form-logout" enctype="multipart/form-data" method="post" action="/logout">
            <input type="hidden" name="csrf_token" value="<?php echo($_SESSION[Session::CSRF_TOKEN_KEY])?>">
            <button class="logout-btn text--gray"><i class="fas fa-power-off"></i></button>
            <button class="logout-btn text--gray">Logout</button>
        </form>
    </nav>

    <!-- Info of user -->
    <div class="user-view__content">
        <div class="content__header">
            <div class="header__account">
                <a href="/"><i class="fas fa-arrow-left text--gray"></i></a>
                <div class="account__info">
                    <p class="info__label text--gray">Account</p>
                    <p class="info__content">
                        <?php echo $user->getDisplayName() ?> · <?php echo $user->job_title ?>
                    </p>
                </div>
            </div>

            <div class="btn btn-edit show-form"
            ><span><i class="fas fa-arrow-up"></i></span> Edit my
                account
            </div
            >
        </div>

        <div class="content__detail" >
            <div class="user-detail">
                <form id="profile-image-form" action="/profile-image" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo($_SESSION[Session::CSRF_TOKEN_KEY])?>">
                    <img class="user-detail__image" src="/public/img/users/<?php echo $user->photo ?>">
                    <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
                    <input
                            id="profile-image-upload"
                            type="file"
                            name="photo"
                    >
                </form>
                <div class="user-detail__info">
                    <p class="info__name"><?php echo $user->getDisplayName() ?></p>
                    <p class="info__job text--gray">
                        <?php echo $user->job_title ?>
                    </p>
                    <p class="info__email">
                        <b>Email address:</b> <?php echo $user->email ?>
                    </p>
                    <p class="info__phone">
                        <b>Phone number:</b> <?php echo $user->phone ?>
                    </p>
                </div>
            </div>

            <div class="user-contact other-info">
                <p class="info__title text--gray">Contact info</p>
                <div class="info__content">
                    <p class="label text--gray"><b>Address</b></p>
                    <p class="value">
                        <?php echo $user->address ?>
                    </p>
                </div>
            </div>

            <div class="user-groups other-info">
                <p class="info__title text--gray">User groups (0)</p>
            </div>

            <div class="user-reports other-info">
                <p class="info__title text--gray">Direct reports (0)</p>
            </div>

            <div class="user-education other-info">
                <p class="info__title text--gray">Contact info</p>
                <div class="other-info__list">No information</div>
            </div>

            <div class="user-experiences other-info">
                <p class="info__title text--gray">Work experiences</p>
                <div class="other-info__list">No information</div>
            </div>

            <div class="user-awards other-info">
                <p class="info__title text--gray">Honors and awards</p>
                <div class="other-info__list">No information</div>
            </div>
        </div>
    </div>

    <!-- Right navbar -->
    <div class="user-view__right-menu">
        <div class="menu__header">
            <p class="header__title"><?php echo $user->getDisplayName() ?></p>
            <p class="header__content text--gray">
                @<?php echo $user->username ?> · <?php echo $user->email ?>
            </p>
        </div>

        <div class="menu__main">
            <div class="menu__el menu__account-info">
                <p class="title text--gray">Account information</p>
                <nav class="right-nav__container">
                    <ul class="right-nav">
                        <li class="right-nav__el right-nav__el--active">
                            <i class="fas fa-cog"></i>
                            <p class="el__label">Account overview</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-pen"></i>
                            <p class="el__label nav__edit show-form">Edit account</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-globe"></i>
                            <p class="el__label">Edit language</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-exclamation-circle"></i>
                            <p class="el__label">Edit password</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-palette"></i>
                            <p class="el__label">Edit theme color</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="far fa-clock"></i>
                            <p class="el__label">Set timesheet</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-shield-alt"></i>
                            <p class="el__label">
                                2-factor authentication
                            </p>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="menu__el">
                <p class="title text--gray">Application & Security</p>
                <nav class="right-nav__container">
                    <ul class="right-nav"></ul>
                </nav>
            </div>

            <div class="menu__el">
                <p class="title text--gray">Advance setting</p>
                <nav class="right-nav__container">
                    <ul class="right-nav">
                        <li class="right-nav__el">
                            <i class="far fa-clock"></i>
                            <p class="el__label">Login histories</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-tv"></i>
                            <p class="el__label">
                                Manage linked devices
                            </p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-envelope"></i>
                            <p class="el__label">
                                Edit email notification
                            </p>
                        </li>
                        <li class="right-nav__el">
                            <i class="far fa-clock"></i>
                            <p class="el__label">Edit timezone</p>
                        </li>
                        <li class="right-nav__el">
                            <i class="fas fa-code-branch"></i>
                            <p class="el__label">On-leave delegation</p>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="layout hidden"></div>

<form class="form form-edit hidden" action="/profile" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="csrf_token" value="<?php echo($_SESSION[Session::CSRF_TOKEN_KEY])?>">
    <div class="form__header">
        <p class="form__title">Edit personal account</p>
        <div class="form__close-container close-form">
            <i class="fas fa-times form__close"></i>
        </div>
    </div>

    <div class="form__content">
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Your first name</p>
                <p class="form__sub-label">Your first name</p>
            </div>
            <input value="<?php echo $user->firstname ?>" type="text" name="firstname"
                   required
                   class="<?php echo $model->hasError("firstname") ? "is-invalid" : "" ?>">
            <div class="invalid-feedback"><?php echo $model->getFirstError("firstname") ?></div>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Your last name</p>
                <p class="form__sub-label">Your last name</p>
            </div>
            <input value="<?php echo $user->lastname ?>" type="text" name="lastname"
                   required
                   class="<?php echo $model->hasError("lastname") ? "is-invalid" : "" ?>">
            <div class="invalid-feedback"><?php echo $model->getFirstError("lastname") ?></div>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Your email address</p>
                <p class="form__sub-label">Your email address</p>
            </div>
            <input
                    value="<?php echo $user->email ?>"
                    type="email"
                    name="email"
                    disabled
                    class="<?php echo $model->hasError("email") ? "is-invalid" : "" ?>">
            <div class="invalid-feedback"><?php echo $model->getFirstError("email") ?></div>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Username</p>
                <p class="form__sub-label">Your username</p>
            </div>
            <input value="@<?php echo $user->username ?>" type="text" name="username" disabled
                   class="<?php echo $model->hasError("username") ? "is-invalid" : "" ?>">
            <div class="invalid-feedback"><?php echo $model->getFirstError("username") ?></div>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Job title</p>
                <p class="form__sub-label">Job title</p>
            </div>
            <input
                    value="<?php echo $user->job_title ?>"
                    type="text"
                    name="job_title"
                    class="<?php echo $model->hasError("job_title") ? "is-invalid" : "" ?>">
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Profile image</p>
                <p class="form__sub-label">Profile image</p>
            </div>
            <input type="hidden" name="MAX_FILE_SIZE" value="300000000" />
            <input type="file" name="photo">
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Date of birth</p>
                <p class="form__sub-label">Date of birth</p>
            </div>
            <input value="<?php echo $model->birthday ?>" type="date" name="birthday"/>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Your phone number</p>
                <p class="form__sub-label">Your phone number</p>
            </div>
            <input value="<?php echo $model->phone ?>" type="text" name="phone"
                   class="<?php echo $model->hasError("phone") ? "is-invalid" : "" ?>"/>
            <div class="invalid-feedback"><?php echo $model->getFirstError("phone") ?></div>
        </div>
        <div class="form__group">
            <div class="form__label-container">
                <p class="form__label">Current address</p>
                <p class="form__sub-label">Current address</p>
            </div>
            <input
                    value="<?php echo $model->address ?>"
                    type="text"
                    name="address"
            />
        </div>
    </div>

    <div class="form__button">
        <button type="button" class="btn btn-cancel close-form">Cancel</button>
        <button class="btn btn-update">Update</button>
    </div>
</form>