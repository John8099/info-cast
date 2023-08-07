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
          <div class="row justify-content-center">
            <div class="col info-box rounded col-lg-10 col-xl-8 bg-light p-4 m-3 ">
              <div class="row ">
                <div class="col-12 col-sm-2 d-flex justify-content-center align-items-center mb-2 mb-sm-0 text-primary" style="font-size: 5.75rem;">
                  <i class="mdi mdi-information"><!-- / --></i>
                </div>
                <div class="col-12 col-sm-10 pl-sm-0 mb-2 d-flex align-items-center">
                  <p class="lh-sm h3">
                    The infocast mobile apps for school deals with the school activities.
                    <br>
                    It helps the management to share the important announcement, current and future events notices, circular, intimations about students and Heads meets.
                    <br>
                    The school can establish news by updating them every event on school and especially all activities of every Department.
                  </p>
                </div>
              </div>

            </div>
          </div>
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