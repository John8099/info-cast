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
              <h4 class="card-title m-0">Teachers</h4>

            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="teachersTable" class="table table-striped">
                  <thead>
                    <tr>
                      <th>Avatar</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Contact</th>
                      <th>Registered Date</th>
                      <th>Registered Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($user) :
                      $adminQuery = mysqli_query(
                        $conn,
                        "SELECT * FROM users WHERE `role`='teacher' "
                      );
                      while ($userRes = mysqli_fetch_object($adminQuery)) :
                    ?>
                        <tr>
                          <td class="py-1">
                            <img src="<?= getAvatar($userRes->id) ?>" alt="image" style="object-fit: cover;">
                          </td>
                          <td> <?= getFullName($userRes->id, "with_middle") ?> </td>
                          <td><?= $userRes->email ?></td>
                          <td><?= $userRes->contact ?></td>
                          <td><?= date("m-d-Y", strtotime($userRes->created_at)) ?></td>
                          <td><?= date("H:i:s a", strtotime($userRes->created_at)) ?></td>
                        </tr>
                    <?php endwhile;
                    endif; ?>
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
    const tableId = "#teachersTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: false,
      order: [
        [1, 'asc']
      ],
      info: true,
      autoWidth: false,
      responsive: true,
      columnDefs: [{
        "targets": [0],
        "orderable": false
      }],
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