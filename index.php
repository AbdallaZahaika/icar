<?php

require_once 'app/helpers.php';

session_start();

$title_page = 'Home Page';
?>

<?php include_once './tpl/header.php'; ?>

<!-- MAIN -->
<main class="container flex-fill">

  <section id="main-top-content">
    <div class="row">
      <div class="col-12 mt-5 text-center">
        <h1 class="display-3 text-primary">Welcome Cars Lovers</h1>
        <p>here you will be found all people love cars</p>
        <?php if (!user_auth()) : ?>
          <p class="mt-4">
            <a href="./signup.php" class="btn btn-outline-success btn-lg">
              Join Us!
            </a>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section id="main-content">
    <div class="row mb-2">
      <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary">Highlights</strong>
            <h3 class="mb-0">Highlighted cars</h3>
            <div class="mb-1 text-muted">Nov 12</div>
            <p class="card-text mb-auto">The most amazing cars you had ever whiteness.</p>

          </div>
          <div class="col-auto d-none d-lg-block">
            <img height="250px" src="images/highlight_cars.jpg" alt="">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success">Priceless</strong>
            <h3 class="mb-0">The most priceless cars</h3>
            <div class="mb-1 text-muted">Nov 11</div>
            <p class="mb-auto">The most priceless cars in the world!
            <p>

          </div>
          <div class="col-auto d-none d-lg-block">
            <img height="250px" src="images/priceless_cars.jpg" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include_once './tpl/footer.php'; ?>