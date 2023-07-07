<?php
include("../../backend/nodes.php");

if (!$isLogin) {
  header("location: ../../index.php");
} else if ($user && $user->role != "admin") {
  header("location: ../user");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("../../components/header-links.php") ?>
  <style>
    select.form-control {
      padding: 5px 10px;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <?php include("../../components/admin-menu.php") ?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
              <h4 class="card-title m-0">Alumnus List</h4>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="alumnusTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Avatar</th>
                      <th>Name</th>
                      <th>Course</th>
                      <th>Year - Section</th>
                      <th>School Year</th>
                      <th>Email</th>
                      <th>Contact</th>
                      <th>Created Date</th>
                      <th>Set to Graduate</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getStudentData = getTableWithWhere("users", "role='alumni'");
                    foreach ($getStudentData as $student) :
                      $course = "";
                      $courseData = getTableSingleDataById("course", "course_id", $student->course_id);
                      if ($courseData) {
                        $course = "($courseData->acronym) $courseData->name";
                      }
                    ?>
                      <tr>
                        <td class="py-1">
                          <img src="<?= getAvatar($student->id) ?>" alt="image" style="object-fit: cover;">
                        </td>
                        <td> <?= getFullName($student->id) ?> </td>
                        <td><?= $course ?></td>
                        <td><?= "$student->year-$student->section" ?></td>
                        <td><?= $student->sy ?></td>
                        <td><?= $student->email ?></td>
                        <td><?= $student->contact ?></td>
                        <td><?= date("m-d-Y", strtotime($student->created_at)) ?></td>
                        <td><?= date("m-d-Y", strtotime("$student->set_graduate_at")) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("../../components/scripts.php"); ?>
</body>
<script>
  function setStudentToGraduate(studentIds) {
    swal.showLoading()
    if (studentIds.length > 0) {
      const backendLoc = createBackendUrl("set_graduate")
      $.post(
        backendLoc, {
          ids: studentIds
        },
        (data, success) => {
          const resp = $.parseJSON(data)
          swal.fire({
            title: resp.success ? "Success" : "Error",
            html: resp.message,
            icon: resp.success ? "success" : "error"
          }).then(() => resp.success ? window.location.reload() : undefined)
        })
    } else {
      swal.fire({
        html: "An error ocurred while updating the data.<br>Please try again later.",
        icon: "error"
      })
    }
  }
  $(document).ready(function() {

    const tableId = "#alumnusTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: true,
      ordering: true,
      info: true,
      autoWidth: false,
      responsive: true,
      select: true,
      language: {
        searchBuilder: {
          button: 'Filter',
        }
      },
      buttons: [{
        extend: 'searchBuilder',
        config: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8]
        }
      }],
      dom: `Bfrtip`,
    });

    table.buttons().container()
      .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
  });
</script>

</html>