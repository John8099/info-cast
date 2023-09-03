<?php
include("../../backend/nodes.php");

if (!$isLogin) {
  header("location: ../../index.php");
} else if ($user && $user->role != "admin") {
  header("location: ../../user");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../../components/header-links.php") ?>
</head>

<body>
  <div class="container-scroller">
    <?php include("../../components/admin-menu.php"); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="container">
            <?php include("../../components/announcement-card.php"); ?>
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
<script>
  $(".announce_sel").select2()
  $(".notify_sel").select2({
    placeholder: "-- select notify to --"
  })
  $(".course_sel").select2()

  $("#form-new-announce").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()
    const backendLoc = createBackendUrl("new_announcement")
    $.post(
      backendLoc,
      $(this).serialize(),
      (data, success) => {
        const resp = $.parseJSON(data)
        swal.fire({
          title: resp.success ? "Success" : "Error",
          html: resp.message,
          icon: resp.success ? "success" : "error"
        }).then(() => resp.success ? window.location.href = './announcements' : undefined)
      })
  })

  $("#form-edit-announce").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()
    const backendLoc = createBackendUrl("edit_announcement")
    $.post(
      backendLoc,
      $(this).serialize(),
      (data, success) => {
        const resp = $.parseJSON(data)
        swal.fire({
          title: resp.success ? "Success" : "Error",
          html: resp.message,
          icon: resp.success ? "success" : "error"
        }).then(() => resp.success ? window.location.href = './announcements' : undefined)
      })
  })
</script>

</html>