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
          <?php if (isset($_GET['page'])) : ?>
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
                    <?php if ($_GET['page'] == "add") : ?>
                      <form id="add-course" action="POST">

                        <div class="form-group">
                          <label>Name</label>
                          <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label>Acronym</label>
                          <input type="text" name="acronym" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <label>Active</label>
                          <label class="toggle-switch ms-2">
                            <input type="checkbox" name="active" checked>
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

                    <?php elseif ($_GET['page'] == "edit") : ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
                <h4 class="card-title m-0">Course List</h4>

                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href = ('./courses?page=add')">
                  New Course
                </button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="courseTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Acronym</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $courseData = getTableData("course");

                      foreach ($courseData as $course) :
                      ?>
                        <tr>
                          <td> <?= $course->name ?> </td>
                          <td> <?= $course->acronym ?> </td>
                          <td> <?= $course->status ?> </td>
                          <td>
                            <button type="button" class="btn btn-sm btn-warning m-1" onclick="return window.location.replace('./courses?page=edit&&course_id=<?= $course->course_id ?>')">
                              Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger m-1" onclick="deleteData('course', 'course_id', '<?= $course->course_id ?>')">
                              Edit
                            </button>
                          </td>

                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>

  <?php include("../../components/scripts.php"); ?>
</body>
<script>
  $(document).ready(function() {
    const tableId = "#courseTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: false,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      language: {
        searchBuilder: {
          button: 'Filter',
        }
      },
      buttons: [{
        extend: 'searchBuilder',

      }],
      dom: 'Bfrtip',
    });

    table.buttons().container()
      .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
  });
</script>

</html>