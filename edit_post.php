<?php

require_once './app/helpers.php';

session_start();
if (!user_auth()) {
    header('location: ./signin.php');
    exit();
}

$title_page = 'Edit Post Form';

// get post
$post = null;

if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

    if ($pid) {
        $uid  = $_SESSION['user_id'];

        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
        mysqli_set_charset($link, 'utf8mb4');

        $pid = mysqli_real_escape_string($link, $pid);
        $sql = "SELECT * FROM posts WHERE id = $pid AND user_id = $uid";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $post = mysqli_fetch_assoc($result);
        }
    }
}

if (!isset($post)) {
    header('location: ./blog.php');
    exit();
}


$errors = [
    'title' => '',
    'article' => ''
];

if (validate_csrf() && isset($_POST['submit'])) {

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING);

    $is_form_valid = true;

    if (!$title || mb_strlen($title) <= 2) {
        $is_form_valid = false;
        $errors['title'] = '* Title is required for minimum 2 characters';
    }
    if (!$article || mb_strlen($article) <= 2) {
        $is_form_valid = false;
        $errors['article'] = '* Article is required for minimum 2 characters';
    }
    if ($is_form_valid) {

        $title = mysqli_real_escape_string($link, $title);
        $article = mysqli_real_escape_string($link, $article);

        $sql = "UPDATE posts SET title = '$title', article = '$article' WHERE id = $pid AND user_id = $uid";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_affected_rows($link) > 0) {
            header('location: ./blog.php');
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
                <h1 class="display-3 text-primary">Add a New Post</h1>
            </div>
        </div>
    </section>

    <section id="main-content">
        <div class="row">
            <div class="col-md-6">
                <form action="" method="POST" novalidate="novalidate" autocomplete="off">

                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">

                    <div class="form-group">
                        <label for="title">
                            <span class="text-danger">*</span> Title
                        </label>
                        <input value="<?= old('title') ? old('title') : htmlentities($post['title']); ?>" type="text" name="title" id="title" class="form-control">
                        <?php if ($errors['title']) : ?>
                            <span class="text-danger"><?= $errors['title']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="article">
                            <span class="text-danger">*</span> Article
                        </label>
                        <textarea name="article" id="article" cols="30" rows="10" class="form-control"><?= old('article') ?  old('article') : htmlentities($post['article']); ?></textarea>
                        <?php if ($errors['article']) : ?>
                            <span class="text-danger"><?= $errors['article']; ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="submit" value="Save Post" name="submit" class="btn btn-primary">
                    <a href="./blog.php" class="btn btn-secondary">Cancel</a>

                </form>
            </div>
        </div>
    </section>

</main>

<?php include_once './tpl/footer.php'; ?>