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
        <div class="content-wrapper row justify-content-center">
          <div class="card  col-md-8 p-0">
            <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
              <h4 class="card-title m-0">Add new admin</h4>

              <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.replace('./admins')">
                Go back
              </button>
            </div>
            <div class="card-body ">
              <form id="form-add-admin" class="forms-sample" enctype="multipart/form-data">
                <?= generateImgUpload() ?>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>First name</label>
                      <input type="text" class="form-control" name="fname" placeholder="First name" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Middle name</label>
                      <input type="text" class="form-control" name="mname" placeholder="Middle name">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last name</label>
                      <input type="text" class="form-control" name="lname" placeholder="Last name">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Email address</label>
                  <input type="email" class="form-control" name="email" placeholder="Email address" required>
                </div>
                <div class="form-group">
                  <label>Contact number</label>
                  <input type="text" class="form-control" name="contact" placeholder="Contact number" required>
                </div>

                <button type="submit" class="btn btn-primary me-2">Submit</button>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include("../../components/scripts.php"); ?>
</body>
<script>
  $("#clear").hide();

  $("#form-add-admin").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()
    $.ajax({
      url: '<?= $SERVER_NAME ?>/backend/nodes?action=add_admin',
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = JSON.parse(data);
        swal.fire({
          title: resp.success ? 'Success!' : "Error!",
          html: resp.message,
          icon: resp.success ? 'success' : 'error',
        }).then(() => resp.success ? window.location.replace("<?= $SERVER_NAME ?>/views/admin/admins") : undefined)
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