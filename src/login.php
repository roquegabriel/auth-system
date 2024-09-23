<?php

$errors = [];
$inputs = [];

if (is_user_logged_in()) {

    redirect_to('index.php');
}

if (is_post_request()) {

    [$inputs, $errors] = filter($_POST, [
        'username' => 'string | required',
        'password' => 'string | required',
        'remember_me' => 'string'
    ]);


    if ($errors) {

        redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
    }

    //if login fails
    if (!login($inputs['username'], $inputs['password'], isset($inputs['remember_me']))) {

        $errors['login'] = 'Invalid username or password';

        redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
    }

    //login successfully
    redirect_to('index.php');
} elseif (is_get_request()) {

    [$errors, $inputs] = session_flash('errors', 'inputs');
}
