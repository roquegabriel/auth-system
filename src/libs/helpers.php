<?php

/**
 * Display a view
 * @param string $filename
 * @param array $data 
 * @return void
 */
function view(string $filename, array $data = []): void
{
    //create variables from the associative array
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    require_once __DIR__ . '/../inc/' . $filename . '.php';
}

/**
 * Returns true if the request method is post
 * 
 * @return boolean
 * 
 */

function is_post_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
}

/**
 * Returns true if the request method is get
 * 
 * @return boolean
 * 
 */
function is_get_request(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
}


/**
 * Returns the error class if error is found in the array $errors
 * @param array $errors
 * @param string $field
 * @return string
 */

function error_class(array $errors, string $field): string
{
    return isset($errors[$field]) ? 'd-block invalid-feedback' : '';
}


/**
 * Returns to another URL
 * @param string $url
 * @return void
 */
function redirect_to(string $url): void
{
    header('Location:' . $url);
    exit;
}


/**
 * Returns to a URL with data stored in the items array
 * @param string $url
 * @param array $items
 * @return void
 */
function redirect_with(string $url, array $items): void
{
    foreach ($items as $key => $value) {
        $_SESSION[$key] = $value;
    }
    redirect_to($url);
}


/**
 * Returns to a URL with a flash message
 * @param string $url
 * @param string $message
 * @param string $type
 * @return void
 */
function redirect_with_message(string $url, string $message, string $type = FLASH_SUCCESS): void
{
    flash('flash_' . uniqid(), $message, $type);
    redirect_to($url);
}

/**
 * Flash data specified by $key from the $_SESSION
 * @param ...$key
 * @return array
 */

function session_flash(...$keys): array
{
    $data = [];
    foreach ($keys as $key) {
        if (isset($_SESSION[$key])) {
            $data[] = $_SESSION[$key];
            unset($_SESSION[$key]);
        } else {
            $data[] = [];
        }
    }
    return $data;
}

function generate_activation_code(): string
{
    return bin2hex(random_bytes(16));
}

function send_activation_email(string $email, string $activation_code): void
{
    //create the activation link
    $activation_link = APP_URL . "/activate.php?email=$email&activation_code=$activation_code";

    //set email subject and body
    $subject = "Please activate your account";
    $message = <<<MESSAGE
    Hi,
    Please click the following link to activate your account:
    $activation_link
    MESSAGE;

    // email header
    $header = "FROM:" . SENDER_EMAIL_ADDRESS;

    //send the email
    mail($email, $subject, nl2br($message), $header);
}

function delete_user_by_id(int $id, int $active = 0)
{
    $sql = "DELETE FROM users
    WHERE id = :id AND active = :active";

    $statement = db()->prepare($sql);

    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':active', $active, PDO::PARAM_INT);

    return $statement->execute();
}

function find_unverified_user(string $activation_code, string $email)
{
    $sql = "SELECT id, activation_code, activation_expiry < now() as expired
    FROM users
    WHERE active = 0 AND email= :email";

    $statement = db()->prepare($sql);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        //already expired, delete the inactive user with expired activation code
        if ((int)$user['expired'] === 1) {
            delete_user_by_id($user['id']);
            return null;
        }
        // verify the password
        if (password_verify($activation_code, $user['activation_code'])) {
            return $user;
        }
    }
    return null;
}

function activate_user(int $user_id): bool
{
    $sql = "UPDATE users
        SET active = 1,
        activated_at = CURRENT_TIMESTAMP
        WHERE id=:id";

    $statement = db()->prepare($sql);
    $statement->bindValue(':id', $user_id, PDO::PARAM_INT);

    return $statement->execute();
}
