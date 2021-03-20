<?php

require_once 'app/helpers.php';

session_start();


if (!user_auth()) {
    header('location: ./signin.php');
    exit();
}
$titlePage = 'Settings';



// get userInfo

$userInfo = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $uid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($uid == $_SESSION['user_id']) {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
        mysqli_set_charset($link, 'utf8mb4');
        $sql = "SELECT * FROM `users` WHERE id = $uid";
        $result = mysqli_query($link, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $userInfo = mysqli_fetch_assoc($result);
        }
    }
}

if (!isset($userInfo)) {
    header('location: ./blog.php');
    exit();
}


$errors = [
    'name' => '',
    'submit' => ''
];

if (validate_csrf() && isset($_POST['submit'])) {

    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($link, $name);


    $is_form_valid = true;
    define('MAX_FILE_SIZE', 1024 * 1024 * 5);

    if (!$name || mb_strlen($name) < 2 || mb_strlen($name) > 70) {
        $errors['name'] = '* Name is required for minimum 2 characters and maximum 70';
        $is_form_valid = false;
    }

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
        if (isset($profile_image)) {
            $sql  = "UPDATE users SET name = '$name',  profile_image = '$profile_image' WHERE `id` = $uid";
        } else {
            $sql  = "UPDATE `users` SET `name` = '$name' WHERE `id` = $uid";
        }
        $result = mysqli_query($link, $sql);
        if ($result && mysqli_affected_rows($link) >= 0) {
            $_SESSION['user_name'] = $name;
            $oldImage = $userInfo['profile_image'];
            if (isset($profile_image) && $oldImage !== 'default_profile.png') {
                unlink("./images/$oldImage");
            }
            header("location: ./");
            exit();
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
                <h1 class="display-3 text-primary">Settings</h1>
                <div>
                    <img src="./images/<?= $userInfo['profile_image'] ?>" alt="<?= $userInfo['profile_image'] ?>" width="100" style="margin-bottom: 20px;">
                </div>
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
                        <input value="<?= old('name') ? old('name') : htmlentities($userInfo['name']); ?>" type="text" name="name" id="name" class="form-control">
                        <?php if ($errors['name']) : ?>
                            <span class="text-danger"><?= $errors['name']; ?></span>
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
                    <div class="d-flex justify-content-between">
                        <input type="submit" value="save" name="submit" class="btn btn-primary">
                        <a href="./changePassword.php">change passwrod</a>
                    </div>

                </form>

            </div>
        </div>
    </section>
</main>

<?php include_once './tpl/footer.php'; ?>