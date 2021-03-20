<?php

require_once 'app/helpers.php';

session_start();

$title_page = 'About us';
?>

<?php include_once './tpl/header.php'; ?>

<!-- MAIN -->
<main class="container flex-fill">

    <section id="main-top-content">
        <div class="row">
            <div class="col-12 mt-5 text-center">
                <h1 class="display-3 text-primary">About ME</h1>
                <h2>I am Abdalla Zahaika, learning full-stack developer</h2>
                <h3> and this is my first project with php. </h3>
                <h4>This website will help you find people love car like you</h4>
                <h5> this is my GitHub account</h5>
                <a href="https://github.com/AbdallaZahaika" target="_blank">https://github.com/AbdallaZahaika</a>
                <h5> this is my linkedin account</h5>
                <a href="https://www.linkedin.com/in/abdalla-zahaika-8a7ba7206/" target="_blank">https://www.linkedin.com/in/abdalla-zahaika-8a7ba7206/</a>




            </div>
        </div>
    </section>

</main>

<?php include_once './tpl/footer.php'; ?>