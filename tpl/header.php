<?php define('LOGO', 'i<i class="fas fa-car"></i>CAR'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iCAR <?= isset($title_page) ? " | $title_page" : ''; ?> </title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel="stylesheet" href="./css/style.css" />

    <!-- JS -->
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

    <script defer src="./js/script.js"></script>

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg-primary shadow-lg">
            <div class="container">
                <!-- LOGO -->
                <a class="navbar-brand" href="./">
                    <?= LOGO; ?>
                </a>
                <!-- BOOTSTRAP NAVBAR TOGGLER -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- NAVBAR LINKS -->
                <div class="collapse navbar-collapse" id="navbarsExample04">
                    <!-- NAVBAR LEFT LINKS -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./blog.php">Blog</a>
                        </li>
                    </ul>
                    <!-- NAVBAR RIGHT LINKS -->
                    <ul class="navbar-nav ml-auto">
                        <?php if (!user_auth()) : ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="./signin.php">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="./signup.php">Sign Up</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $_SESSION['user_name']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="./settings.php?id=<?= $_SESSION['user_id'] ?>">settings <i class="fas fa-user-cog"></i></a>
                                    <a class="dropdown-item" href="./logout.php">Sign Out <i class="fas fa-sign-out-alt"></i></a>
                                </div>
                            </li>
                            <li class="nav-item">

                            </li>

                        <?php endif; ?>

                    </ul>
                </div>

            </div>
        </nav>
    </header>