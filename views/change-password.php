<?php
include("../backend/nodes.php");
if (!$isLogin) {
  header("location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../components/header-links.php") ?>
</head>

<body>
  <div class="container-scroller">
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="main-panel">
          <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
              <div class="col-lg-5 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                  <form class="pt-3" method="POST" id="form-change-password">
                    <div class="form-group">
                      <label>Old Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                          <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-shield text-primary"></i>
                          </span>
                        </div>
                        <input type="password" name="old" id="inputOldPassword" placeholder="Old Password" class="form-control pass" required>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>New Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                          <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-shield text-primary"></i>
                          </span>
                        </div>
                        <input type="password" name="new" id="inputNewPassword" placeholder="New Password" class="form-control pass" required>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Confirm Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend bg-transparent">
                          <span class="input-group-text bg-transparent border-right-0">
                            <i class="mdi mdi-shield text-primary"></i>
                          </span>
                        </div>
                        <input type="password" id="inputConfirmPassword" placeholder="Current Password" class="form-control pass" required>
                      </div>
                    </div>

                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" id="checkShow">
                        Show passwords
                      </label>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                      <button type="submit" class="btn btn-primary m-2">UPDATE</button>
                      <button type="button" onclick="return goBack()" class="btn btn-danger m-2">CANCEL</button>
                    </div>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
</body>
<script>
  $("#checkShow").on("click", function() {
    if ($(this).prop("checked")) {
      $(".pass").attr("type", "text")
    } else {
      $(".pass").attr("type", "password")
    }
  })

  $("#form-change-password").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()

    const oldPass = $("#inputOldPassword").val()
    const newPass = $("#inputNewPassword").val()
    const confirmPass = $("#inputConfirmPassword").val()

    if (newPass !== confirmPass) {
      swal.fire({
        title: "Error",
        html: "New Password and Confirm Password not match",
        icon: "error",
      })
    } else {
      const backendLoc = createBackendUrl("change_password")
      $.post(
        backendLoc,
        $(this).serialize(),
        (data, success) => {
          const resp = $.parseJSON(data)
          swal.fire({
            title: resp.success ? "Success!" : "Error!",
            html: resp.message,
            icon: resp.success ? "success" : "error"
          }).then(() => resp.success ? window.location.href = './admin/' : undefined)
        })
    }


  })
</script>

</html>