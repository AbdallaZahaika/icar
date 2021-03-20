<?php

require_once 'app/helpers.php';

session_start();
if (user_auth()) {
    header('location: ./');
    exit();
}

$title_page = 'Sign In';

$errors = [
    'email' => '',
    'password' => '',
    'submit' => ''
];

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$email) {
        $errors['email'] = '* A valid email is required';
    } elseif (!$password) {
        $errors['password'] = '* Please enter your password';
    } else {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

        $email = mysqli_real_escape_string($link, $email);
        $password = mysqli_real_escape_string($link, $password);

        $sql = "SELECT u.*
        FROM users u 
        WHERE email='$email' LIMIT 1";
        $result =  mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                login_user($user['id'], $user['name'],  './blog.php');
            }
        } else {
            $errors['submit'] = '* Wrong email or password';
        }
    }
}
?>

<?php include_once './tpl/header.php'; ?>

<!-- MAIN -->
<main class="container flex-fill">

    <section id="main-top-content">
        <div class="row">
            <div class="col-12 mt-5 text-center">
                <h1 class="display-3 text-primary">Sign in with your account</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio cumque error ut vitae debitis ratione aperiam inventore perferendis reprehenderit doloribus.</p>
            </div>
        </div>
    </section>

    <section id="main-content">
        <div class="row">
            <div class="col-12 col-md-6 mt-3 mx-auto">

                <form action="" method="POST" novalidate="1" autocomplete="off">
                    <div class="form-group">
                        <label for="email"><span class="text-danger">*</span> Email:</label>
                        <input type="email" name="email" id="email" class="form-control">
                        <?php if ($errors['email']) : ?>
                            <span class="text-danger"><?= $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password"><span class="text-danger">*</span> Password:</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <?php if ($errors['password']) : ?>
                            <span class="text-danger"><?= $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="submit" value="Sign In" name="submit" class="btn btn-primary">
                    <span class="ml-4 text-danger"><?= $errors['submit']; ?></span>
                </form>

            </div>
        </div>
    </section>
</main>

<?php include_once './tpl/footer.php'; ?>