<?php

//constants
const FLASH = 'FLASH_MESSAGES';

const FLASH_ERROR = 'danger';
const FLASH_INFO = 'info';
const FLASH_WARNING = 'warning';
const FLASH_SUCCESS = 'success';

/** Create a flash message 
 *@param string $name
 *@param string $message
 *@param string $type
 *@return void
 */

function create_flash_message(string $name, string $message, string $type): void
{
    //remove existing message with the name
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }

    //add the message to the session
    $_SESSION[FLASH][$name] = [
        'message' => $message   ,
        'type' => $type
    ];
}

/**
 * Format a flash message
 * @param array $flash_message
 * @return string 
 */
function format_flash_message(array $flash_message): string
{
    return sprintf(
        '<div class="alert alert-%s">%s</div>',
        $flash_message['type'],
        $flash_message['message']
    );
}

/**
 * Display a flash message
 * @param string $name
 * @return void
 */

function display_flash_message(string $name): void
{
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }
    // get the message from session
    $flash_message = $_SESSION[FLASH][$name];

    //delete the message
    unset($_SESSION[FLASH][$name]);

    //display the message
    echo format_flash_message($flash_message);
}

/**
 * Display all messages
 * @return void
 */

function display_all_flash_messages(): void
{
    if (!isset($_SESSION[FLASH])) {
        return;
    }
    //get all the flash messages
    $flash_messages = $_SESSION[FLASH];

    //remove all messages
    unset($_SESSION[FLASH]);

    //show all messages
    foreach ($flash_messages as $fm) {
        echo format_flash_message($fm);
    }
}

/**
 * Flash message
 * @param string $name
 * @param string $message
 * @param string $type (error, warning, success, info)
 * @return void
 */

function flash(string $name = '', string $message = '', string $type = ''): void
{
    if ($name !== '' && $message !== '' && $type !== '') {
        create_flash_message($name, $message, $type);
    } elseif ($name !== '' && $message === '' && $type === '') {
        display_flash_message($name);
    } elseif ($name === '' && $message === '' && $type === '') {
        display_all_flash_messages();
    }
}
