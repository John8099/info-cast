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
    <?php include("../../components/admin-menu.php") ?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
              <h4 class="card-title m-0">Add new admin</h4>

              <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.replace('./admins')">
                Go back
              </button>
            </div>
            <div class="card-body">

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include("../../components/scripts.php"); ?>
</body>
<script>

</script>

</html>