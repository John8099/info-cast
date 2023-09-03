<?php include("./backend/nodes.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("./components/header-links.php") ?>

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
                  <div class="brand-logo">
                    <img class="w-100" src="<?= $SERVER_NAME ?>/public/logo2.png" alt="logo">
                  </div>

                  <h3 class="text-center">Login you account</h3>
                  <form class="pt-3" method="POST" id="form-login">
                    <div class="input-group mb-2">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                          <i class="mdi mdi-at text-primary"></i>
                        </span>
                      </div>
                      <input type="email" name="email" class="form-control" placeholder="Email address" required>
                    </div>

                    <div class="input-group mb-2">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                          <i class="mdi mdi-shield text-primary"></i>
                        </span>
                      </div>
                      <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required>
                    </div>

                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" id="checkShow">
                        Show password
                      </label>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                      <button type="submit" class="btn btn-primary">SIGN IN</button>
                    </div>
                    <div class="text-center mt-4 font-weight-light">
                      Don't have an account? <a href="./create-account" class="text-primary">Create</a>
                    </div>
                  </form>
                  <?php password_hash("test", PASSWORD_DEFAULT) ?>
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

  <?php include("./components/scripts.php") ?>
</body>
<script>
  $("#checkShow").on("click", function() {
    if ($(this).prop("checked")) {
      $("#inputPassword").attr("type", "text")
    } else {
      $("#inputPassword").attr("type", "password")
    }
  })

  $("#form-login").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()
    const backendLoc = createBackendUrl("login")
    $.post(
      backendLoc,
      $(this).serialize(),
      (data, success) => {
        const resp = $.parseJSON(data)
        if (!resp.success) {
          swal.fire({
            title: "Error!",
            html: resp.message,
            icon: "error"
          })
        } else if (resp.success && resp.isNew === "1") {
          swal.fire({
            title: "Your account is newly created",
            text: "Would you like to change the password?",
            icon: "question",
            confirmButtonText: "Yes",
            cancelButtonColor: "#dc3545",
            showCancelButton: true,
            cancelButtonText: "No"
          }).then((d) => {
            if (d.isConfirmed) {
              window.location.href = ("<?= $SERVER_NAME ?>/views/change-password");
            } else {
              window.location.replace("<?= $SERVER_NAME ?>/views/admin/")
            }
          });
        } else {
          let location = "<?= $SERVER_NAME ?>/views/";
          if (resp.role === "admin") {
            location += "admin/"
          } else {
            location += "user/announcements"
          }

          window.location.replace(location)
        }
      }
    )
  })
</script>

</html>