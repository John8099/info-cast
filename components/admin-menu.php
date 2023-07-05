<div class="horizontal-menu">

  <nav class="navbar top-navbar col-lg-12 col-12 p-0">
    <div class="container-fluid">
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">

        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.html">
            <img src="<?= $SERVER_NAME ?>/assets/images/logo.svg" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="index.html">
            <img src="<?= $SERVER_NAME ?>/assets/images/logo-mini.svg" alt="logo" />
          </a>
        </div>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <span class="nav-profile-name">
                <?= $user ? getFullName($user->id) : "" ?>
              </span>
              <img src="<?= getAvatar($user ? $user->id : null) ?>" alt="profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="mdi mdi-account-settings text-primary"></i>
                My Profile
              </a>
              <a href="<?= $SERVER_NAME ?>/backend/nodes?action=logout" class="dropdown-item">
                <i class="mdi mdi-logout text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </div>
  </nav>

  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        <?php
        include_once("links.php");
        $self = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

        $navBarLinks = array_filter(
          $links,
          fn ($val) => in_array($user->role, $val["allowedViews"]),
          ARRAY_FILTER_USE_BOTH
        );
        foreach ($navBarLinks as $key => $value) :
        ?>
          <li class="nav-item <?= $value["url"] == str_replace(".php", "", $self) ? "active" : ""  ?>">
            <a class="nav-link" href="<?= $value["url"] ?>">
              <i class="<?= $value["config"]["icon"] ?>  menu-icon"></i>
              <span class=" menu-title"><?= $value["title"] ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>
</div>