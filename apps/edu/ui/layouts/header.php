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
    

      <!-- Logo on the Right -->
      <div class="dropdown text-start p-1">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo $_SESSION['profile_image'] ?>" alt="mdo" width="32" height="32" class="rounded-circle">
        </a>
        <ul class="dropdown-menu text-small">
          <li><a class="dropdown-item" href="/apps/edu/ui/views/profile/profile.php?ui=<?php echo $_SESSION['id']; ?>"><span class="fs-14 text-black flex-grow"><?php echo $_SESSION['full_name']; ?></span></a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/apps/edu/ui/views/settings/settings.php?form=profile">Settings</a></li>
            <li><a class="dropdown-item" href="/apps/edu/ui/views/finance/finances.php">Help desk</a></li>
            <li><a class="dropdown-item" href="/logout">Logout</a></li>
        </ul>
      </div>

    </div>
  </div>
</header>

