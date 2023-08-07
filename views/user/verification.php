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
            <div class="col-md-5">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0 p-2">Upload School ID</h5>
                </div>
                <div class="card-body">
                  <?php if ($user) : ?>
                    <form method="POST" id="form_verification" enctype="multipart/form-data">
                      <input type="text" name="id" value="<?= $user->id ?>" readonly hidden>

                      <div class="row mb-3">
                        <?= generateImgUpload(null, "$SERVER_NAME/public/id-card.png") ?>
                      </div>

                      <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  <?php endif; ?>
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
<script>
  $("#form_verification").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()

    const backendLoc = createBackendUrl("verify_account")

    $.ajax({
      url: backendLoc,
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = $.parseJSON(data)
        swal.fire({
          title: resp.success ? "Success" : "Error",
          html: resp.message,
          icon: resp.success ? "success" : "error"
        }).then(() => resp.success ? window.location.replace("<?= $SERVER_NAME ?>/views/user/announcements") : undefined)
      },
      error: function(data) {
        swal.fire({
          title: 'Oops...',
          text: 'Something went wrong.',
          icon: 'error',
        })
      }
    });
  })
</script>

</html>