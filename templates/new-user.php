<section class="content__side">
    <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

    <a class="button button--transparent content__side-button" href="register.php">Войти</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="register.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <?php $classname = isset($errors['email']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname;?>" type="text" name="email" id="email" value="<?= getPostVal('email'); ?>" placeholder="Введите e-mail">
            <?php if (isset($errors['email'])) : ?>
                <p class="form__message"><?= $errors['email'] ?></p>
            <?php endif; ?>
            <!--<p class="form__message">E-mail введён некорректно</p>-->
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <?php $classname = isset($errors['password']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname;?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">
            <?php if (isset($errors['password'])) : ?>
                <p class="form__message"><?= $errors['password'] ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>
            <?php $classname = isset($errors['user_name']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname;?>" type="text" name="user_name" id="name" value="<?= getPostVal('user_name'); ?>" placeholder="Введите имя">
            <?php if (isset($errors['user_name'])) : ?>
                <p class="form__message"><?= $errors['user_name'] ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row form__row--controls">
            <!--<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>-->
            <?php if (isset($errors)): ?>
                <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
            <?php endif; ?>
            <input class="button" type="submit" name="" value="Зарегистрироваться">
        </div>
    </form>
</main>
