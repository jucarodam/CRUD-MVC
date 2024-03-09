<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo APP_URL; ?>">
      <img src="<?php echo APP_URL; ?>app/views/img/Applus-K2-logo.png" alt="ApplusK2-CRUD" width="200" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="<?php echo APP_URL; ?>productList/">Product List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo APP_URL; ?>newProduct/">New Product</a>
        </li>
      </ul>
    </div>
  </div>
</nav>