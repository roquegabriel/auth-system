<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title><?= $title ?? 'Home' ?></title>
</head>

<body>
    <div class="container vh-75">

        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-around py-3 mb-4 border-bottom sticky-top">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                    My company
                </a>
            </div>

            <?php if (!is_user_logged_in()) : ?>


                <div class="col-md-2 text-end ms-auto">
                    <a class="btn btn-primary me-2 <?= $login ?? '' ?>" href="/auth/public/login.php" role="button">Login</a>
                    <a class="btn btn-primary <?= $signin ?? '' ?>" href="/auth/public/register.php" role="button">Sign-up</a>
                </div>

            <?php else : ?>


                <div class="flex-shrink-0 dropdown ms-2">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

                        <svg width="36" height="36" class="rounded-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <path d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z" />
                        </svg>

                        <?= current_user() ?> 

                    </a>
                    <ul class="dropdown-menu text-small shadow">
                        <li> <a href="#" class="dropdown-item">Settings</a> </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/auth/public/logout.php">Sign out</a></li>
                    </ul>

                </div>
            <?php endif; ?>




        </header>


        <div class="row justify-content-center align-content-center h-100">
            <div class="col-xl-4">
                <?php flash() ?>