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
          <?php if (isset($_GET['page'])) : ?>
            <div class="row justify-content-center">
              <div class="col-md-7">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
                    <h4 class="card-title m-0">Add new admin</h4>

                    <button type="button" class="btn btn-secondary btn-sm" onclick="return goBack()">
                      Go back
                    </button>
                  </div>
                  <div class="card-body">
                    <form id="form-add-admin" class="forms-sample" enctype="multipart/form-data">
                      <?= generateImgUpload() ?>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>First name</label>
                            <input type="text" class="form-control" name="fname" placeholder="First name" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Middle name</label>
                            <input type="text" class="form-control" name="mname" placeholder="Middle name">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Last name</label>
                            <input type="text" class="form-control" name="lname" placeholder="Last name" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" name="email" placeholder="Email address" required>
                      </div>
                      <div class="form-group">
                        <label>Contact number</label>
                        <input type="text" class="form-control" name="contact" placeholder="Contact number" required>
                      </div>

                      <div class="mt-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary m-1">
                          Submit
                        </button>
                        <button type="submit" class="btn btn-danger m-1" onclick="return goBack()">
                          Cancel
                        </button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php else : ?>
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
                <h4 class="card-title m-0">Admin List</h4>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href = ('./admins?page=add')">
                  New Admin
                </button>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="adminTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Created Date</th>
                        <th>Created Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if ($user) :
                        $adminQuery = mysqli_query(
                          $conn,
                          "SELECT * FROM users WHERE `role`='admin' and id <> '$user->id'"
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
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php include("../../components/scripts.php"); ?>
</body>
<script>
  $("#clear").hide();

  $("#form-add-admin").on("submit", function(e) {
    e.preventDefault()
    swal.showLoading()
    $.ajax({
      url: '<?= $SERVER_NAME ?>/backend/nodes?action=add_admin',
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        const resp = JSON.parse(data);
        swal.fire({
          title: resp.success ? 'Success!' : "Error!",
          html: resp.message,
          icon: resp.success ? 'success' : 'error',
        }).then(() => resp.success ? window.location.replace("<?= $SERVER_NAME ?>/views/admin/admins") : undefined)
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

  $(document).ready(function() {
    const tableId = "#adminTable";
    var table = $(tableId).DataTable({
      paging: true,
      lengthChange: false,
      order: [
        [1, 'asc']
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
        "targets": [0],
        "orderable": false
      }],
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