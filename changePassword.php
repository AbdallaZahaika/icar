<?php

require_once 'app/helpers.php';

session_start();
if (!user_auth()) {
    header('location: ./');
    exit();
}

$title_page = 'Sign In';

$errors = [
    'password' => '',
    'newPasswrod' => '',
    'confirmPassword' => '',
    'submit' => ''
];

if (isset($_POST['submit'])) {

    // ///////////  Reagex Password
    $lowerCaseRegExp = '/(?=.*[a-z])/';
    $upperCaseRegExp = '/(?=.*[A-Z])/';
    $numericRegExp = '/(?=.*[0-9])/';
    //////////////////////////////////

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $newPasswrod = filter_input(INPUT_POST, 'newPasswrod', FILTER_SANITIZE_STRING);
    $confirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING);
    /////////Pasword
    if (!preg_match($lowerCaseRegExp, $_POST['password'])) {
        $errors['password'] = '* one lowerCase Required!';
    } elseif (!preg_match(
        $upperCaseRegExp,
        $_POST['password']
    )) {
        $errors['password'] = '* one upperCase Required!';
    } elseif (!preg_match($numericRegExp, $_POST['password'])) {
        $errors['password'] = '* one number Required!';
    }
    ///////// New Pasword
    if (!preg_match($lowerCaseRegExp, $_POST['newPasswrod'])) {
        $errors['newPasswrod'] = '* one lowerCase Required!';
    } elseif (!preg_match(
        $upperCaseRegExp,
        $_POST['newPasswrod']
    )) {
        $errors['newPasswrod'] = '* one upperCase Required!';
    } elseif (!preg_match($numericRegExp, $_POST['newPasswrod'])) {
        $errors['newPasswrod'] = '* one number Required!';
    }
    ///////// confirm Pasword
    if (!preg_match($lowerCaseRegExp, $_POST['confirmPassword'])) {
        $errors['confirmPassword'] = '* one lowerCase Required!';
    } elseif (!preg_match(
        $upperCaseRegExp,
        $_POST['confirmPassword']
    )) {
        $errors['confirmPassword'] = '* one upperCase Required!';
    } elseif (!preg_match($numericRegExp, $_POST['confirmPassword'])) {
        $errors['confirmPassword'] = '* one number Required!';
    }

    if ($confirmPassword && $newPasswrod === $confirmPassword) {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
        $password = mysqli_real_escape_string($link, $password);
        $newPasswrod = mysqli_real_escape_string($link, $newPasswrod);
        $user_id  =  $_SESSION['user_id'];
        $sql = "SELECT u.*
        FROM users u 
        WHERE id='$user_id' LIMIT 1";
        $result =  mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $newPasswrod = password_hash($newPasswrod, PASSWORD_BCRYPT);
                $sql = "UPDATE users  SET `password` = '$newPasswrod' Where id = $user_id ";
                $result =  mysqli_query($link, $sql);
                header("location: ./");
                exit();
            } else {
                $errors['submit'] = '* Wrong  password';
            }
        } else {
            $errors['submit'] = '* Wrong  password';
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
                <h1 class="display-3 text-primary">Change Passwrod</h1>
            </div>
        </div>
    </section>

    <section id="main-content">
        <div class="row">
            <div class="col-12 col-md-6 mt-3 mx-auto">

                <form action="" method="POST" novalidate="1" autocomplete="off">
                    <div class="form-group">
                        <label for="password"><span class="text-danger">*</span> Password:</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <?php if ($errors['password']) : ?>
                            <span class="text-danger"><?= $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="newPasswrod"><span class="text-danger">*</span>New Password:</label>
                        <input type="password" name="newPasswrod" id="newPasswrod" class="form-control">
                        <?php if ($errors['newPasswrod']) : ?>
                            <span class="text-danger"><?= $errors['newPasswrod']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword"><span class="text-danger">*</span> confirm Password:</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
                        <?php if ($errors['confirmPassword']) : ?>
                            <span class="text-danger"><?= $errors['confirmPassword']; ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="submit" value="change passwrod" name="submit" class="btn btn-primary">
                    <span class="ml-4 text-danger"><?= $errors['submit']; ?></span>
                </form>

            </div>
        </div>
    </section>
</main>

<?php include_once './tpl/footer.php'; ?>