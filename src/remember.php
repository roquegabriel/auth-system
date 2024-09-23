<?php

function generate_tokens(): array
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));

    return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token): ?array
{
    $parts = explode(':', $token);
    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function insert_user_tokens(int $user_id, string $selector, string $hashed_validator, string $expiry): bool
{

    $sql = "INSERT INTO user_tokens(user_id,selector,hashed_validator,expiry)
    VALUES(:user_id,:selector,:hashed_validator,:expiry)";

    $statement = db()->prepare($sql);
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindValue(':selector', $selector, PDO::PARAM_STR);
    $statement->bindValue(':hashed_validator', $hashed_validator, PDO::PARAM_STR);
    $statement->bindValue(':expiry', $expiry, PDO::PARAM_STR);

    return $statement->execute();
}

function find_user_token_by_selector(string $selector)
{
    $sql = "SELECT id,selector,hashed_validator,user_id,expiry
    FROM user_tokens
    WHERE selector = :selector AND
    expiry >= now() LIMIT 1";

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $selector);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function delete_user_token(int $user_id): bool
{

    $sql = "DELETE FROM user_tokens WHERE user_id = :user_id";

    $statement = db()->prepare($sql);
    $statement->bindValue(':user_id', $user_id);

    return $statement->execute();
}

function find_user_by_token(string $token)
{
    $tokens = parse_token($token);

    if (!$tokens) {
        return null;
    }

    $sql = "SELECT users.id, username 
    FROM users
    INNER JOIN user_tokens ON user_id = users.id 
    WHERE selector = :selector AND expiry > now() 
    LIMIT 1";

    $statement = db()->prepare($sql);
    $statement->bindValue(':selector', $tokens[0]);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function token_is_valid(string $token): bool
{
    [$selector, $validator] = parse_token($token);
    $tokens = find_user_token_by_selector($selector);
    if (!$tokens) {
        return false;
    }

    return password_verify($validator, $tokens['hashed_validator']);
}
