<?php

require_once 'app/helpers.php';

session_start();

if (user_auth()) {
    header('location: ./');
    exit();
}

$titlePage = 'Sign up Page';

$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'submit' => ''
];

if (validate_csrf() && isset($_POST['submit'])) {

    /// db connect
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

    ////////// Reagex ///////////////
    ////////// email reagex
    $emailRegExp = '/^[a-z0-9\._\-\+]{2,50}@[a-z\-0-9]+(\.[a-z]{2,10})+$/i';
    // ///////////  Reagex Password
    $lowerCaseRegExp = '/(?=.*[a-z])/';
    $upperCaseRegExp = '/(?=.*[A-Z])/';
    $numericRegExp = '/(?=.*[0-9])/';
    //////////////////////////////////

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($link, $name);

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($link, $email);

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($link, $password);

    $is_form_valid = true;
    $profile_image = 'default_profile.png';
    define('MAX_FILE_SIZE', 1024 * 1024 * 5);

    if (!$name || mb_strlen($name) < 2 || mb_strlen($name) > 70) {
        $errors['name'] = '* Name is required for minimum 2 characters and maximum 70';
        $is_form_valid = false;
    }
    if (email_exists($link, $email)) {
        $errors['email'] = '* Email is already taken';
        $is_form_valid  = false;
    }
    if (!preg_match($emailRegExp, $_POST['email'])) {
        $errors['email'] = '* email not valid';
        $is_form_valid  = false;
    }

    /////////Pasword
    if (!preg_match($lowerCaseRegExp, $_POST['password'])) {
        $errors['password'] = '* one lowerCase Required!';
        $is_form_valid = false;
    } elseif (!preg_match($upperCaseRegExp, $_POST['password'])) {
        $errors['password'] = '* one upperCase Required!';
        $is_form_valid = false;
    } elseif (!preg_match($numericRegExp, $_POST['password'])) {
        $errors['password'] = '* one number Required!';
        $is_form_valid = false;
    }
    //////////////////////////////////


    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    $image = $_FILES['image'] ?? null;

    if (
        $is_form_valid &&
        isset($image) &&
        isset($image['name']) &&
        $image['error'] === 0 &&
        $image['size'] <= MAX_FILE_SIZE &&
        is_uploaded_file($image['tmp_name']) &&
        in_array(pathinfo($image['name'])['extension'], $allowed)
    ) {
        $profile_image =  date('Y.m.d.H.i.s') . '-' . $image['name'];
        move_uploaded_file($image['tmp_name'], 'images/' . $profile_image);
    }

    if ($is_form_valid) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $sql  = "INSERT INTO users (name, email, password,profile_image) VALUES ('$name', '$email', '$password','$profile_image')";
        $result = mysqli_query($link, $sql);
        if ($result && mysqli_affected_rows($link) > 0) {
            $new_user_id = mysqli_insert_id($link);
            login_user($new_user_id, $name, './blog.php');
        }
    }
}

$csrf_token = csrf();
?>

<?php include_once './tpl/header.php'; ?>

<!-- MAIN -->
<main class="container flex-fill">

    <section id="main-top-content">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="display-3 text-primary">Sign up for a new account</h1>
            </div>
        </div>
    </section>

    <section id="main-content">
        <div class="row">
            <div class="col-md-6">
                <form action="" method="POST" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data">

                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                    <div class="form-group">
                        <label for="name">
                            <span class="text-danger">*</span> Name
                        </label>
                        <input value="<?= old('name'); ?>" type="text" name="name" id="name" class="form-control">
                        <?php if ($errors['name']) : ?>
                            <span class="text-danger"><?= $errors['name']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <span class="text-danger">*</span> Email
                        </label>
                        <input value="<?= old('email'); ?>" type="text" name="email" id="email" class="form-control">
                        <?php if ($errors['email']) : ?>
                            <span class="text-danger"><?= $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <span class="text-danger">*</span> Password
                        </label>
                        <input type="password" name="password" id="password" class="form-control">
                        <?php if ($errors['password']) : ?>
                            <span class="text-danger"><?= $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="image">Profile Image</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input">
                                <label for="image" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Sign up" name="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
    </section>
</main>

<?php include_once './tpl/footer.php'; ?>