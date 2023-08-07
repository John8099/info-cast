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
    <?php
    if ($user && $user->role == "admin") {
      include("../components/admin-menu.php");
    } else {
      include("../components/user-menu.php");
    }
    ?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="card">
                <div class="card-body pt-3">

                  <!-- Bordered Tabs -->
                  <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">
                      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Profile</button>
                    </li>

                    <li class="nav-item">
                      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                    </li>

                  </ul>
                  <div class="tab-content pt-2">
                    <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                      <!-- Profile Edit Form -->
                      <?php if ($user) : ?>
                        <form method="POST" id="formUserData" enctype="multipart/form-data">
                          <input type="text" name="id" value="<?= $user->id ?>" hidden readonly>
                          <input type="text" name="role" value="<?= $user->role ?>" hidden readonly>

                          <div class="row mb-3">
                            <?= generateImgUpload($user->id) ?>
                          </div>

                          <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">First name</label>
                            <div class="col-md-8 col-lg-9">
                              <input name="fname" type="text" class="form-control" value="<?= $user->fname ?>" required>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Middle name</label>
                            <div class="col-md-8 col-lg-9">
                              <input name="mname" type="text" class="form-control" value="<?= $user->mname ?>">
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Last name</label>
                            <div class="col-md-8 col-lg-9">
                              <input name="lname" type="text" class="form-control" value="<?= $user->lname ?>" required>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Email</label>
                            <div class="col-md-8 col-lg-9">
                              <input name="email" type="email" class="form-control" value="<?= $user->email ?>" required>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label class="col-md-4 col-lg-3 col-form-label">Contact</label>
                            <div class="col-md-8 col-lg-9">
                              <input name="contact" type="text" class="form-control" value="<?= $user->contact ?>" required>
                            </div>
                          </div>

                          <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                          </div>
                        </form>
                      <?php endif; ?>
                      <!-- End Profile Edit Form -->

                    </div>

                    <div class="tab-pane fade pt-3" id="profile-change-password">
                      <!-- Change Password Form -->
                      <?php if ($user) : ?>
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
                          </div>

                        </form>
                      <?php endif; ?>
                      <!-- End Change Password Form -->

                    </div>

                  </div><!-- End Bordered Tabs -->

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("../components/scripts.php"); ?>
</body>
<script>
  $('button[data-bs-toggle="tab"]').on("click", function(e) {
    if (e.target.innerHTML === "Profile") {
      $("#inputOldPassword").val("")
      $("#inputNewPassword").val("")
      $("#inputConfirmPassword").val("")
      $("#checkShow").prop("checked", false);
    }
  })

  $("#checkShow").on("click", function() {
    if ($(this).prop("checked")) {
      $(".pass").attr("type", "text")
    } else {
      $(".pass").attr("type", "password")
    }
  })

  $("#formUserData").on("submit", function(e) {
    e.preventDefault();
    swal.showLoading();

    const backendLoc = createBackendUrl("update_user")

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
          title: resp.success ? "Success!" : "Error!",
          html: resp.message,
          icon: resp.success ? "success" : "error"
        }).then(() => resp.success ? window.location.reload() : undefined)
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