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
    .onoffswitch {
      position: relative;
      width: 90px;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
    }

    .onoffswitch-checkbox {
      display: none;
    }

    .onoffswitch-label {
      display: block;
      overflow: hidden;
      cursor: pointer;
      border: 2px solid #ced4da;
      border-radius: 20px;
    }

    .onoffswitch-inner {
      display: block;
      width: 200%;
      margin-left: -100%;
      -moz-transition: margin 0.3s ease-in 0s;
      -webkit-transition: margin 0.3s ease-in 0s;
      -o-transition: margin 0.3s ease-in 0s;
      transition: margin 0.3s ease-in 0s;
    }

    .onoffswitch-inner:before,
    .onoffswitch-inner:after {
      display: block;
      float: left;
      width: 50%;
      height: 30px;
      padding-left: 15px;
      line-height: 30px;
      font-size: 14px;
      color: white;
      font-family: Trebuchet, Arial, sans-serif;
      font-weight: bold;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }

    .onoffswitch-inner:before {
      content: "ON";
      background-color: #198754 !important;
      color: #fff !important;
    }

    .onoffswitch-inner:after {
      content: "OFF";
      padding-right: 25px;
      background-color: #dc3545 !important;
      color: #fff !important;
      text-align: right;
    }

    .onoffswitch-switch {
      display: block;
      width: 18px;
      margin: 6px;
      background: #FFFFFF;
      border: 2px solid #ced4da;
      border-radius: 20px;
      position: absolute;
      top: 0;
      bottom: 0;
      right: 56px;
      -moz-transition: all 0.3s ease-in 0s;
      -webkit-transition: all 0.3s ease-in 0s;
      -o-transition: all 0.3s ease-in 0s;
      transition: all 0.3s ease-in 0s;
    }

    .onoffswitch-checkbox:checked+.onoffswitch-label .onoffswitch-inner {
      margin-left: 0;
    }

    .onoffswitch-checkbox:checked+.onoffswitch-label .onoffswitch-switch {
      right: 0px;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <?php include("../../components/admin-menu.php") ?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
                  <h4 class="card-title m-0">Settings</h4>
                </div>
                <div class="card-body">
                  <?php
                  $getSetting = getTableData("settings");
                  $setting = $getSetting[0];
                  ?>
                  <h4 class="text-center">Teacher Registration</h4>
                  <div class="onoffswitch m-auto">
                    <input type="checkbox" class="onoffswitch-checkbox" <?= $setting->teacher_reg == "1" ? "checked" : "" ?> id="teacherRegSwitch">
                    <label class="onoffswitch-label" for="teacherRegSwitch">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </div>
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
  $("#teacherRegSwitch").on("change", function() {
    swal.showLoading()
    const backendLoc = createBackendUrl("update_settings")
    $.post(
      backendLoc, {
        teacher_reg: $(this).get(0).checked
      },
      (data, success) => {
        const resp = $.parseJSON(data)
        if (!resp.success) {
          swal.fire({
            title: "Error",
            html: resp.message,
            icon: "error"
          }).then(() => window.location.reload())
        }
        swal.close()
      })
  })
</script>

</html>