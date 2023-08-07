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
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
              <h4 class="card-title m-0">Student List</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="studentTable" class="table table-striped">
                  <thead>
                    <tr>
                      <td></td>
                      <th>Avatar</th>
                      <th>Name</th>
                      <th>Course</th>
                      <th>Year - Section</th>
                      <th>School Year</th>
                      <th>Email</th>
                      <th>Contact</th>
                      <th>Registered Date</th>
                      <th>Registered Time</th>
                      <th class="d-none"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getStudentData = getTableWithWhere("users", "role='student' and is_verified='2'");
                    foreach ($getStudentData as $student) :
                      $course = "";
                      $courseData = getTableSingleDataById("course", "course_id", $student->course_id);
                      if ($courseData) {
                        $course = "($courseData->acronym) $courseData->name";
                      }
                    ?>
                      <tr>
                        <td></td>
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
                        <td><?= date("H:i:s a", strtotime($student->created_at)) ?></td>
                        <td class="d-none"><?= $student->id ?></td>
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
  function setStudentToAlumni(studentIds) {
    swal.showLoading()
    if (studentIds.length > 0) {
      const backendLoc = createBackendUrl("set_alumni")
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
      select: true,
      language: {
        searchBuilder: {
          button: 'Filter',
        }
      },
      columnDefs: [{
          orderable: false,
          className: 'select-checkbox',
          targets: 0
        },
        {
          "targets": [0, 1],
          "orderable": false
        },
      ],
      select: {
        style: 'multi',
        selector: 'td:first-child'
      },
      buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
        'selectAll',
        'selectNone',
        {
          text: 'Set Alumni',
          action: function() {
            let count = table.rows({
              selected: true
            }).count();

            if (count > 0) {
              let selectedIds = [];

              const selectedRow = table.rows({
                selected: true
              }).data();

              selectedRow.each((data) => {
                selectedIds.push(data[data.length - 1])
              })

              setStudentToAlumni(selectedIds)

            } else {
              swal.fire({
                html: "Cannot set to alumni with no selected row(s).",
                icon: "warning"
              })
            }

          }
        }
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