<?php include("./backend/nodes.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>InfoCast</title>
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
                  <!-- <div class="brand-logo">
                    <img src="../../images/logo.svg" alt="logo">
                  </div> -->
                  <p>Logo here...</p>
                  <h4>InfoCast</h4>
                  <h6 class="font-weight-light">Sign in to continue.</h6>
                  <form class="pt-3" method="POST" id="form-login">
                    <div class="input-group mb-2">
                      <div class="input-group-prepend bg-transparent">
                        <span class="input-group-text bg-transparent border-right-0">
                          <i class="mdi mdi-account-outline text-primary"></i>
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
                      <input type="password" class="form-control" id="inputPassword" placeholder="Password" required>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                      <button type="submit" class="btn btn-block btn-primary">SIGN IN</button>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" id="checkShow">
                        Show password
                      </label>
                    </div>
                    <div class="text-center mt-4 font-weight-light">
                      Don't have an account? <a href="register.html" class="text-primary">Create</a>
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
        swal.fire({
          title: resp.success ? "Success!" : "Error!",
          html: resp.message,
          icon: resp.success ? "success" : "error"
        }).then(() => {
          if (!resp.success) return

          let location = "<?= $SERVER_NAME ?>/views/";
          if (resp.role === "admin") {
            location += "admin/"
          } else {
            location += "user/"
          }
          window.location.replace(location)
        })
      }
    )
  })
</script>

</html>