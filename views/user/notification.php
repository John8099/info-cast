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
    <?php include("../../components/user-menu.php") ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <section class="section-50">
            <div class="container">

              <div class="row justify-content-center">
                <div class="col-md-7">
                  <?php
                  if ($user) :
                    $notificationData = getTableWithWhere("notification", "user_id = $user->id ORDER BY notification_id DESC");
                  ?>
                    <div class="notification-ui_dd-content ">
                      <?php foreach ($notificationData as $notification) : ?>
                        <div class="notification-list">
                          <div class="notification-list_content">
                            <div class="notification-list_img">
                              <img src="<?= getAvatar($notification->created_by) ?>" alt="user">
                            </div>
                            <div class="notification-list_detail">
                              <p><b><?= getFullName($notification->created_by) ?></b> </p>
                              <p class="text-muted">
                                <?= $notification->content ?>
                              </p>
                            </div>
                          </div>
                          <div class="notification-list_feature-img">
                            <p class="text-muted">
                              <small>
                                <?= get_time_ago(strtotime($notification->created_at)) ?>
                              </small>
                            </p>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <?php if (count($notificationData) == 0) : ?>
                      <div class="card">
                        <div class="card-body">
                          <h3>No Notifications yet.</h3>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>

            </div>
        </div>

        <!-- <div class="text-center">
                <a href="#!" class="dark-link">Load more activity</a>
              </div> -->

      </div>
      </section>
    </div>
    <!-- content-wrapper ends -->

  </div>
  <!-- main-panel ends -->
  </div>
  <!-- The Modal Image -->

  <!-- page-body-wrapper ends -->
  </div>

  <!-- container-scroller -->
  <?php include("../../components/scripts.php"); ?>
</body>

</html>