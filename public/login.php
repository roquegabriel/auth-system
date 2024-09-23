<?php

require __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/login.php';

?>

<?php view('header', [
    'title' => 'Login',
    'login' => 'active'
    ]) ?>

<?php if (isset($errors['login'])) : ?>
    <div class="alert alert-danger">
        <?= $errors['login'] ?>
    </div>
<?php endif ?>

<form action="login.php" method="post">
    <h1 class="text-center">Login</h1>
    <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" name="username" id="username" class="form-control" value="<?= $inputs['username'] ?? ''  ?>">
        <div class="<?= error_class($errors, 'username') ?>">
            <?= $errors['username'] ?? '' ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" name="password" id="password" class="form-control" value="<?= $inputs['password'] ?? '' ?>">
        <div class="<?= error_class($errors, 'password') ?>">
            <?= $errors['password'] ?? '' ?>
        </div>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="checked" <?= $inputs['agree'] ?? '' ?>>
        <label class="form-check-label" for="remember_me">Remember me</label>
        <div class="<?= error_class($errors, 'agree') ?>">
            <?= $errors['agree'] ?? '' ?>
        </div>
    </div>

    <div class="d-grid gap-3">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>

<?php view('footer') ?>