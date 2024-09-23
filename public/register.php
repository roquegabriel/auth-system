<?php

include __DIR__ . '/../src/bootstrap.php';
include __DIR__ . '/../src/register.php';

?>

<?php view('header', [
    'title' => 'Register',
    'signin' => 'active'
    ]) ?>

<form action="register.php" method="post">
    <h1 class="text-center">Sign up</h1>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= $inputs['username'] ?? '' ?>">
        <div class="<?= error_class($errors, 'username') ?>">
            <small><?= $errors['username'] ?? '' ?></small>
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $inputs['email'] ?? '' ?>">
        <div class="<?= error_class($errors, 'email') ?>">
            <small><?= $errors['email'] ?? '' ?></small>
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" value="<?= $inputs['password'] ?? '' ?>">
        <div class="<?= error_class($errors, 'password') ?>">
            <small><?= $errors['password'] ?? '' ?></small>
        </div>
    </div>
    <div class="mb-3">
        <label for="password2" class="form-label">Password Again</label>
        <input type="password" class="form-control" id="password2" name="password2" value="<?= $inputs['password2'] ?? '' ?>">
        <div class="<?= error_class($errors, 'password2') ?>">
            <small><?= $errors['password2'] ?? '' ?></small>
        </div>
    </div>
    <div class="mb-3 form-check">
        <label for="agree" class="form-check-label">
            <input type="checkbox" class="form-check-input" name="agree" id="agree" value="checked" <?= $inputs['agree'] ?? '' ?>>
            I agree with the <a href="#">term of services</a>
        </label>
        <div class="<?= error_class($errors, 'agree') ?>">
            <small><?= $errors['agree'] ?? '' ?></small>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </div>
    <footer class="text-center">
        Already a member? <a href="login.php">Login here</a>
    </footer>
</form>

<?php view('footer') ?>