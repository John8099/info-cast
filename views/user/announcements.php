<?php
include("../../backend/nodes.php");

if (!$isLogin) {
  header("location: ../../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../../components/header-links.php") ?>
</head>

<body>
  <div class="container-scroller">
    <?php include("../../components/user-menu.php") ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <?php if ($user) : ?>
            <?php if ($user->is_verified == 0 || $user->is_verified == 3 || $user->is_verified == 1) : ?>
              <div class="row justify-content-center">
                <div class="col rounded col-lg-10 col-xl-8 bg-light p-4 m-3 ">
                  <div class="row ">
                    <div class="col-12 col-sm-2 d-flex justify-content-center align-items-center mb-2 mb-sm-0 text-danger" style="font-size: 5.75rem;">
                      <?php if ($user->is_verified == 0 || $user->is_verified == 3) : ?>
                        <!-- <i class="mdi mdi-account-check text-white"></i> -->
                        <i class="mdi mdi-exclamation text-white"></i>
                      <?php else : ?>
                        <i class="mdi mdi-account-check text-white"></i>
                      <?php endif; ?>

                    </div>
                    <div class="col-12 col-sm-10 pl-sm-0 mb-2 d-flex align-items-center">
                      <p class="h2 lh-base">
                        <?php if ($user->is_verified == 0) : ?>
                          Your account is not yet verified.
                          <br>
                          Please verify your account to view announcements.
                        <?php elseif ($user->is_verified == 1) : ?>
                          Successfully submitted verification.
                          <br>
                          Please wait for the admin to review your verification.
                        <?php elseif ($user->is_verified == 3) : ?>
                          Your verification is declined.
                          <br>
                          This may due to blur image or wrong verification submitted.
                        <?php endif; ?>
                      </p>
                    </div>
                    <?php if ($user->is_verified == 0 || $user->is_verified == 3) : ?>
                      <div class="col-12 mb-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary" onclick="return window.location.href = './verification'">
                          Verify Account
                        </button>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php else : ?>

            <?php endif; ?>
          <?php endif; ?>
        </div>
        <!-- content-wrapper ends -->

      </div>
      <!-- main-panel ends -->
    </div>
    <!-- The Modal Image -->

    <!-- page-body-wrapper ends -->
  </div>

  <!-- container-scroller -->
  <?php include("../../components/scripts.php"); ?>
</body>

</html>