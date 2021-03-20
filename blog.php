<?php

require_once './app/helpers.php';

session_start();

$title_page = 'Blog Page';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
mysqli_set_charset($link, 'utf8mb4');

$sql = "SELECT u.name,u.profile_image, p.*, DATE_FORMAT(p.date, '%d/%m/%Y %H:%i:%s') pdate FROM posts p
JOIN users u ON u.id = p.user_id
ORDER BY p.date DESC";
$result = mysqli_query($link, $sql);
?>

<?php include_once './tpl/header.php'; ?>

<!-- MAIN -->
<main class="container flex-fill">

    <section id="main-top-content">
        <div class="row">
            <div class="col-12 mt-5">
                <h1 class="display-3 text-primary"><?= LOGO; ?> Blog</h1>
                <?php if (user_auth()) : ?>
                    <a href="./add_post.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add New Post
                    </a>
                <?php else : ?>
                    To add post <a href="./signup.php">create a user</a> or <a href="./signin.php">sign in</a>.
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="main-content">
        <div class="row">
            <div class="col-12 mt-5">
                <h3><?= LOGO; ?> Posts</h3>
            </div>
        </div>

        <?php if ($result  && mysqli_num_rows($result) > 0) : ?>
            <?php while ($post = mysqli_fetch_assoc($result)) : ?>
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-header">
                                <img src="./images/<?= $post['profile_image'] ?>" alt="" width="50">
                                <span><?= $post['name']; ?></span>
                                <span class="float-right"><?= $post['pdate']; ?></span>
                            </div>
                            <div class="card-body">
                                <h4><?= htmlentities($post['title']); ?></h4>
                                <p><?= nl2br(htmlentities($post['article'])); ?></p>


                                <?php if (user_auth() && $_SESSION['user_id'] == $post['user_id']) : ?>

                                    <div class="float-right">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle dropdown-toggle-no-arrow text-dark" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="./edit_post.php?pid=<?= $post['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>
                                                <a class="dropdown-item delete-post-btn" href="./delete_post.php?pid=<?= $post['id']; ?>&csrf_token=<?= csrf(); ?>">
                                                    <i class="fas fa-eraser"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="row">
                <div class="col-12 text-center mt-5">
                    <h3>No posts yet. Be the first to post on our site</h3>
                </div>
            </div>
        <?php endif; ?>

    </section>

</main>

<?php include_once './tpl/footer.php'; ?>