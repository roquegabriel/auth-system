<?php

/**
 * Register a user
 * @param string $username
 * @param string $email
 * @param string $password
 * @param bool $is_admin false
 * @return bool
 */

function register_user(string $email, string $username, string $password, string $activation_code, int $expiry = 1 * 24 * 60 * 60, bool $is_admin = false): bool
{
    $sql = "INSERT INTO users(username,email,password,is_admin,activation_code,activation_expiry)
            VALUES(:username, :email, :password, :is_admin,:activation_code,:activation_expiry)";

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT), PDO::PARAM_STR);
    $statement->bindValue(':is_admin', $username, PDO::PARAM_INT);
    $statement->bindValue(':activation_code', password_hash($activation_code, PASSWORD_DEFAULT), PDO::PARAM_STR);
    $statement->bindValue(':activation_expiry', date('Y-m-d H:i:s', time() + $expiry));

    return $statement->execute();
}

function find_user_by_username(string $username)
{
    $sql = 'SELECT id, username, password, active, email
            FROM users
            WHERE username = :username';

    $statement = db()->prepare($sql);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function is_user_active($user)
{
    return (int)$user['active'] === 1;
}

function login(string $username, string $password, bool $remember = false): bool
{
    $user = find_user_by_username($username);

    //if user found, check the password
    if ($user && is_user_active($user) && password_verify($password, $user['password'])) {

        log_user_in($user);

        if ($remember) {
            remember_me($user['id']);
        }

        return true;
    }
    return false;
}

function is_user_logged_in(): bool
{
    //check the session
    if (isset($_SESSION['username'])) {
        return true;
    }

    //check the remember me in cookie

    $token = isset($_COOKIE['remember_me']) ? htmlspecialchars($_COOKIE['remember_me']) : false;

    if ($token && token_is_valid($token)) {
        $user = find_user_by_token($token);
        if ($user) {
            return log_user_in($user);
        }
    }
    return false;
}

function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}

function logout(): void
{
    if (is_user_logged_in()) {

        //delete the user token
        delete_user_token($_SESSION['user_id']);

        //delete session
        unset($_SESSION['username'], $_SESSION['user_id']);

        //remove the remember me cookie
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', null, -1);
        }
        //remove all session data
        session_destroy();

        //redirect to the login page
        redirect_to('login.php');
    }
}

function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}

function log_user_in(array $user): bool
{
    //prevent session fixation attack
    if (session_regenerate_id()) {
        //set username and id in the session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        return true;
    }
}

function remember_me(int $user_id, int $day = 30)
{
    [$selector, $validator, $token] = generate_tokens();

    //remove all existing token associated with the user id
    delete_user_token($user_id);

    //set expiration date
    $expired_seconds = time() + 60 * 60 * 24 * $day;

    //insert a token to the database
    $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', $expired_seconds);

    if (insert_user_tokens($user_id, $selector, $hash_validator, $expiry)) {
        setcookie('remember_me', $token, $expired_seconds);
    }
}
