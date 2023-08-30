<?php
include("../backend/nodes.php");

if (!$isLogin) {
  header("location: ../index.php");
} else if ($user && $user->role != "admin") {
  header("location: ./user");
} else if ($user && $user->role != "admin") {
  header("location: ./admin");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../components/header-links.php") ?>
</head>

<body>
  <div class="container-scroller">
    <?php include("../components/admin-menu.php") ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="container">
            <?php if ($user) : ?>
              <?php if (isset($_GET["page"])) : ?>
                <?php if ($_GET["page"] == "new") : ?>
                  <div class="row justify-content-center">
                    <div class="col-md-7">
                      <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
                          <h4 class="card-title m-0"><?= $_GET['page'] == "add" ? "Add new" : "Edit" ?> course</h4>
                          <button type="button" class="btn btn-secondary btn-sm" onclick="return goBack()">
                            Go Back
                          </button>
                        </div>
                        <div class="card-body">
                          <?php
                          $name = "";
                          $acronym = "";
                          $status = "active";
                          $formId = "add-course";
                          $courseId = "";

                          if ($_GET["page"] == "edit" && isset($_GET["course_id"])) {
                            $courseData = getTableSingleDataById("course", "course_id", $_GET["course_id"]);
                            $name = $courseData->name;
                            $acronym = $courseData->acronym;
                            $status = $courseData->status;
                            $formId = "edit-course";
                            $courseId = $courseData->course_id;
                          }
                          ?>
                          <form id="" action="POST">

                            <?php if ($_GET["page"] == "edit" && isset($_GET["course_id"])) : ?>
                              <input type="text" name="course_id" value="<?= $courseId ?>" readonly hidden>
                            <?php endif; ?>

                            <div class="form-group">
                              <label>Name</label>
                              <input type="text" name="name" class="form-control" value="<?= $name ?>" required>
                            </div>

                            <div class="form-group">
                              <label>Acronym</label>
                              <input type="text" name="acronym" class="form-control" value="<?= $acronym ?>" required>
                            </div>

                            <div class="form-group">
                              <label>Active</label>
                              <label class="toggle-switch ms-2">
                                <input type="checkbox" name="active" <?= $status == "active" ? "checked" : "" ?>>
                                <span class="toggle-slider round"></span>
                              </label>
                            </div>

                            <div class="mt-3 d-flex justify-content-center">
                              <button type="submit" class="btn btn-primary m-2">
                                Submit
                              </button>
                              <button type="button" class="btn btn-danger m-2" onclick="return goBack()">
                                Cancel
                              </button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>
                <?php else : ?>
                  <!-- Edit -->
                <?php endif; ?>
              <?php else : ?>
                <?php if ($user->role == "admin") : ?>
                  <div class="card">
                    <div class="card-header d-flex justify-content-lg-end justify-content-center">
                      <button type="button" class="btn btn-primary" onclick="window.location.href ='./announcements?page=new'">
                        New announcement
                      </button>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-5 col-lg-3 float-start">
                          <div class="card ">
                            <div class="card-header">
                              <div class="row">
                                <div class="col-6">
                                  Header
                                </div>
                                <div class="col-6 text-end">
                                  <button type="button" class="btn btn-link p-0" data-toggle="modal">
                                    <i class="mdi mdi-pencil text-warning mx-2" title="Edit" data-toggle="tooltip"></i>
                                  </button>
                                  <button type="button" class="btn btn-link p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="mdi mdi-close text-danger ml-2"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                            <div class="card-body  bg-warning text-white">
                              <p class="card-text ">With supporting text below as a natural lead-in to additional content.</p>
                            </div>
                            <div class="card-footer">
                              Date:
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php else : ?>
                  <!-- Other User -->
                <?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>
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
  <?php include("../components/scripts.php"); ?>
</body>
<script>

</script>

</html>