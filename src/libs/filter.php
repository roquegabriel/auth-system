<?php

function filter(array $data, array $fields, array $messages = []): array
{
    $sanitization_rules = [];
    $validation_rules = [];
    
    foreach ($fields as $field => $rule) {
        if (strpos($rule, '|')) {
            [$sanitization_rules[$field], $validation_rules[$field]] = explode('|', $rule, 2);
        } else {
            $sanitization_rules[$field] = $rule;
        }
    }
    $inputs = sanitize($data, $sanitization_rules);
    $errors = validate($inputs, $validation_rules, $messages);

    return [$inputs, $errors];
}
