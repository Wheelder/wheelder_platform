<header class="p-1 mb-2 border-bottom ">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">

      <!-- Dropdown on the Left -->
      <a href="/edu/home" class="d-flex align-right mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
        <img class="bi me-2" width="80" height="60" role="img"  src="/apps/edu/ui/assets/images/logo.png" alt="logo" aria-label="Bootstrap"/>
      </a>
      

      <!-- Search Bar and Other Items in the Middle -->
      <form class="col-8 col-lg-auto mb-3 mb-lg-0 me-lg-3 p-1" role="search" action="" method="POST">
        <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
      </form>
    

      <!-- User dropdown — avatar toggles menu; name shown as first non-clickable label -->
      <div class="dropdown text-start p-1">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo htmlspecialchars($_SESSION['profile_image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="user" width="32" height="32" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small">
          <!-- Full name label — identifies who is logged in without exposing session data in the UI -->
          <li><span class="dropdown-item-text fw-semibold"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></span></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?php echo url('/dashboard'); ?>">Settings</a></li>
          <li><a class="dropdown-item" href="<?php echo url('/help'); ?>">Help desk</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="<?php echo url('/logout'); ?>">Logout</a></li>
        </ul>
      </div>

    </div>
  </div>
</header>

