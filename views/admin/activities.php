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
              <h4 class="card-title m-0">Activities</h4>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="activityLogTable" class="table table-striped text-dark">
                  <thead>
                    <tr>
                      <th>Activity</th>
                      <th>Actioned By</th>
                      <th>Created Date</th>
                      <th>Created Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $activityData = getTableWithWhere("activity", " activity_id <> '' ORDER BY activity_id DESC");
                    foreach ($activityData as $activity) :
                    ?>
                      <tr>
                        <td> <?= $activity->action ?> </td>
                        <td><?= getFullName($activity->user_id, "with_middle") ?></td>
                        <td><?= date("m-d-Y", strtotime($activity->created_at)) ?></td>
                        <td><?= date("h:i:s a", strtotime($activity->created_at)) ?></td>
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
  $(document).ready(function() {
    const tableId = "#activityLogTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: true,
      ordering: false,
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