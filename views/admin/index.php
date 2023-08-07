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
</head>

<body>
  <div class="container-scroller">
    <?php include("../../components/admin-menu.php") ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-3 mb-3">
              <div class="card ">
                <div class="card-body m-0 pt-3 pb-0">
                  <div class="row">
                    <div class="col-6">
                      <?php $student = getTableWithWhere("users", "role='student' and is_verified = '1'") ?>
                      <h2 class="text-dark mb-2 font-weight-bold"><?= count($student) ?></h2>
                      <small class="text-muted">Verified students</small>
                    </div>
                    <div class="col-4 ">
                      <i class="mdi mdi-account-multiple text-primary" style="font-size: 80px;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 mb-3">
              <div class="card ">
                <div class="card-body m-0 pt-3 pb-0">
                  <div class="row">
                    <div class="col-6">
                      <?php $student = getTableWithWhere("users", "role='admin'") ?>
                      <h2 class="text-dark mb-2 font-weight-bold"><?= count($student) ?></h2>
                      <small class="text-muted">Admins</small>
                    </div>
                    <div class="col-4 ">
                      <i class="mdi mdi-account-multiple text-danger" style="font-size: 80px;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 mb-3">
              <div class="card ">
                <div class="card-body m-0 pt-3 pb-0">
                  <div class="row">
                    <div class="col-6">
                      <?php $student = getTableWithWhere("users", "role='alumni'") ?>
                      <h2 class="text-dark mb-2 font-weight-bold"><?= count($student) ?></h2>
                      <small class="text-muted">Alumnus</small>
                    </div>
                    <div class="col-4 ">
                      <i class="mdi mdi-account-multiple text-success" style="font-size: 80px;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 mb-3">
              <div class="card ">
                <div class="card-body m-0 pt-3 pb-0">
                  <div class="row">
                    <div class="col-6">
                      <?php $student = getTableWithWhere("users", "role='teacher'") ?>
                      <h2 class="text-dark mb-2 font-weight-bold"><?= count($student) ?></h2>
                      <small class="text-muted">Teacher</small>
                    </div>
                    <div class="col-4 ">
                      <i class="mdi mdi-account-multiple text-warning" style="font-size: 80px;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
              <h4 class="card-title m-0">Unverified Students</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="studentTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Avatar</th>
                      <th>Verification Image</th>
                      <th>Name</th>
                      <th>Course</th>
                      <th>Year - Section</th>
                      <th>School Year</th>
                      <th>Email</th>
                      <th>Contact</th>
                      <th>Registered Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getStudentData = getTableWithWhere("users", "role='student' and is_verified='1'");
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
                        <td>
                          <?= verificationImg($student->id) ?>
                        </td>
                        <td> <?= getFullName($student->id) ?> </td>
                        <td><?= $course ?></td>
                        <td><?= "$student->year-$student->section" ?></td>
                        <td><?= $student->sy ?></td>
                        <td><?= $student->email ?></td>
                        <td><?= $student->contact ?></td>
                        <td><?= date("Y-m-d", strtotime($student->created_at)) ?></td>
                        <td class="text-center">
                          <button type="button" class="btn btn-success btn-sm m-1" <?= !$student->verification_img ? "disabled" : null ?> onclick="handleVerifyStudent('<?= $student->id ?>', 'approve')">
                            Approve
                          </button>
                          <button type="button" class="btn btn-danger btn-sm m-1" <?= !$student->verification_img ? "disabled" : null ?> onclick="handleVerifyStudent('<?= $student->id ?>', 'decline')">
                            Decline
                          </button>
                        </td>
                      </tr>

                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

      </div>
      <!-- main-panel ends -->
    </div>
    <!-- The Modal Image -->
    <?= generateModalImg("divModalImage", "modalImg", "imgCaption") ?>

    <!-- page-body-wrapper ends -->
  </div>

  <!-- container-scroller -->
  <?php include("../../components/scripts.php"); ?>
</body>
<script>
  function handleVerifyStudent(studentId, action) {
    swal.showLoading()
    swal
      .fire({
        title: `Are you sure you want to ${action} this student?`,
        text: "You can't undo this action after this process.",
        icon: "question",
        confirmButtonText: action === "approve" ? "Approve" : "Decline",
        confirmButtonColor: action === "approve" ? "#0ddbb9" : "#dc3545",
        showCancelButton: true,
      })
      .then((d) => {
        if (d.isConfirmed) {
          swal.showLoading();
          const backendLoc = createBackendUrl("verify_student")
          $.post(
            backendLoc, {
              student_id: studentId,
              action: action
            },
            (data, success) => {
              const resp = $.parseJSON(data)
              swal.fire({
                title: resp.success ? "Success" : "Error",
                html: resp.message,
                icon: resp.success ? "success" : "error"
              }).then(() => resp.success ? window.location.reload() : undefined)
            })
        }
      });
  }

  function handleModalOpen(el) {
    handleOpenModalImg(el, "divModalImage", "modalImg", "imgCaption")
  }

  $(document).ready(function() {

    const tableId = "#studentTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: true,
      order: [
        [2, 'asc']
      ],
      info: true,
      autoWidth: false,
      responsive: true,
      language: {
        searchBuilder: {
          button: 'Filter',
        }
      },
      columnDefs: [{
        "targets": [0, 1, 9],
        "orderable": false
      }],

      buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [2, 3, 4, 5, 6, 7, 8]
          }
        },

      ],
      dom: `
  <'row'
  <'col-md-4 d-flex my-2 justify-content-start'B>
  <'col-md-4 d-flex my-2 justify-content-center'f>
  <'col-md-4 d-flex my-2 justify-content-center'l>
  >
  <'row'<'col-sm-12'tr>>
  <'row'<'col-sm-12'ip>>
  `,
    });

    table.buttons().container()
      .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
  });
</script>

</html>