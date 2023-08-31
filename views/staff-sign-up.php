<?php
include("../backend/nodes.php");
$getSetting = getTableData("settings");
$isOpenRegistration = $getSetting[0]->teacher_reg == "1" ? true : false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../components/header-links.php") ?>

</head>

<body>
  <div class="container-scroller">
    <div class="container-scroller">
      <?php if ($isOpenRegistration) : ?>
        <div class="container-fluid page-body-wrapper full-page-wrapper">
          <div class="main-panel">
            <div class="content-wrapper d-flex align-items-center auth px-0">
              <div class="row w-100 mx-0">
                <div class="col-lg-9 mx-auto">

                  <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="brand-logo d-flex justify-content-center">
                      <img class="w-50" src="<?= $SERVER_NAME ?>/public/logo2.png" alt="logo">
                    </div>
                    <h3 class="text-center">Create your account</h3>
                    <form class="pt-3" method="POST" id="form-create-account" enctype="multipart/form-data">
                      <?= generateImgUpload() ?>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="fname" class="form-control" placeholder="First name" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Middle name</label>
                            <input type="text" name="mname" class="form-control" placeholder="Middle name">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Last name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Last name" required>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Course</label>
                            <select name="course_id" class=" form-control">
                              <option value="" selected disabled>-- Select Course --</option>
                              <?php
                              $getCourseData = getTableData("course");
                              foreach ($getCourseData as $course) :
                              ?>
                                <option value="<?= $course->course_id ?>"><?= "($course->acronym) $course->name" ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Contact number</label>
                            <input type="text" name="contact" class="form-control" placeholder="Contact number" required>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Email address</label>
                        <div class="input-group">
                          <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                              <i class="mdi mdi-at text-primary"></i>
                            </span>
                          </div>
                          <input type="email" name="email" class="form-control" placeholder="Email address" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                          <div class="input-group-prepend bg-transparent">
                            <span class="input-group-text bg-transparent border-right-0">
                              <i class="mdi mdi-shield text-primary"></i>
                            </span>
                          </div>
                          <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required>
                        </div>
                      </div>

                      <div class="form-check">
                        <label class="form-check-label text-muted">
                          <input type="checkbox" class="form-check-input" id="checkShow">
                          Show password
                        </label>
                      </div>

                      <div class="mt-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">CREATE</button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
      <?php else : ?>
        <div class="container">
          <section class="section d-flex flex-column justify-content-center py-4">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-md-10 d-flex flex-column align-items-center justify-content-center">

                  <div class="card mb-3">

                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-5">
                          <div class="pt-4 pb-2">
                            <img src="<?= $SERVER_NAME ?>/public/hazard.png" class="img-fluid">
                          </div>
                        </div>
                      </div>
                      <p class="display-3 pt-4 text-center">
                        Registration is <strong style="color: red"><u>CLOSED</u></strong>
                      </p>
                      <p class="display-6 text-center">
                        Please contact administrator to enable this page.
                      </p>
                      <p class="display-6 text-center">
                        <a href="#" onclick="return window.location.reload()">Restart</a> if page is enabled.
                      </p>
                    </div>
                  </div>

                </div>

              </div>
            </div>

          </section>
        </div>
      <?php endif; ?>
      <!-- page-body-wrapper ends -->
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
</body>
<script>

  $("#clear").hide()
  $("#checkShow").on("click", function() {
    if ($(this).prop("checked")) {
      $("#inputPassword").attr("type", "text")
    } else {
      $("#inputPassword").attr("type", "password")
    }
  })

  $("#form-create-account").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()

    const backendLoc = createBackendUrl("create_staff_account")

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
        }).then(() => resp.success ? window.location.href = '../' : undefined)
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