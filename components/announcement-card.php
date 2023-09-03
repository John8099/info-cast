<?php if ($user) : ?>
  <?php if (isset($_GET["page"])) : ?>
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center pt-3 pb-3">
            <h4 class="card-title m-0"><?= $_GET['page'] == "new" ? "New" : "Edit" ?> Announcement</h4>
            <button type="button" class="btn btn-secondary btn-sm" onclick="return goBack()">
              Go Back
            </button>
          </div>
          <div class="card-body">
            <?php
            $form_id = "form-new-announce";
            $title = "";
            $announce_type = "";
            $notify_to = "";
            $course_id = "";
            $announcement = "";

            if ($_GET["page"] == "edit" && isset($_GET["id"])) {
              $announceData = getTableSingleDataById("announcements", "id", $_GET["id"]);

              $form_id = "form-edit-announce";
              $title = $announceData->title;
              $announce_type = $announceData->announce_type;
              $notify_to = $announceData->notified_to;
              $course_id = $announceData->course_id;
              $announcement = $announceData->announcement;
            }
            ?>
            <form id="<?= $form_id ?>" action="POST">

              <?php if ($_GET["page"] == "edit" && isset($_GET["id"])) : ?>
                <input type="text" name="id" value="<?= $_GET["id"] ?>" readonly hidden>
              <?php endif; ?>

              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= $title ?>" required>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Announcement type</label>
                    <select name="announce_type" class="form-control announce_sel" required>
                      <option value="" selected disabled>-- select announcement type --</option>
                      <?php
                      $announce_type_enum = get_enum_values("announcements", "announce_type");
                      foreach ($announce_type_enum as $a_type) :
                        $selectedAnnounce = isSelected($announce_type, $a_type);
                      ?>
                        <option value="<?= $a_type ?>" <?= $selectedAnnounce ?>><?= ucfirst($a_type) ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Notify to</label>
                    <select name="notify_to[]" class="form-control notify_sel" multiple="multiple" required>
                      <?php
                      $notified_to_arr = array("student", "staff", "teacher", "alumni");
                      sort($notified_to_arr);

                      foreach ($notified_to_arr as $n_type) :
                        $arrNotify = explode(", ", $notify_to);
                        $selectedNotified = in_array($n_type, $arrNotify) ? "selected" : "";
                      ?>
                        <option value="<?= $n_type ?>" <?= $selectedNotified ?>><?= ucfirst($n_type) ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="form-control course_sel">
                  <option value="" selected disabled>-- select course --</option>
                  <?php
                  $getCourseData = getTableData("course");
                  foreach ($getCourseData as $course) :
                    $selected_course = isSelected($course_id, $course->course_id);
                  ?>
                    <option value="<?= $course->course_id ?>" <?= $selected_course ?>><?= "($course->acronym) $course->name" ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label>Announcement</label>
                <textarea name="announcement" class="form-control" cols="30" rows="10" required><?= nl2br($announcement) ?></textarea>
              </div>

              <div class="mt-3 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary m-2">
                  <?= $_GET["page"] == "edit" && isset($_GET["id"]) ? "Edit and Announce" : "Announce" ?>
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
  <?php else :
    $isAdmin = $user->role == "admin" ? true : false;
  ?>
    <div class="card">
      <div class="card-header">
        <div class="row <?= !$isAdmin ? "d-flex justify-content-end" : "" ?>">
          <?php if ($isAdmin) : ?>
            <div class="col-md-8">
              <button type="button" class="btn btn-primary my-2" style="width: 160px; height: 36px;" onclick="window.location.href ='./announcements?page=new'">
                New announcement
              </button>
            </div>
          <?php endif; ?>
          <div class="col-md-4">
            <input type="text" class="form-control rounded" placeholder="Search..." id="search_field" data-list=".livesearch" autocomplete="off">
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row livesearch">
          <?php
          $announcements = getTableWithWhere("announcements", "id IS NOT NULL ORDER BY id DESC");
          $hasAnnounce = false;
          foreach ($announcements as $announce) :

            if ($announce->course_id == $user->course_id || $isAdmin || $announce->course_id == null || $user->course_id == null) :
              $role = $user->role;
              $notifyTo = explode(", ", $announce->notified_to);
              if (in_array($role, $notifyTo) || $isAdmin) :

                $bg = "";

                switch ($announce->announce_type) {
                  case "info":
                    $bg = "bg-info";
                    break;
                  case "event":
                    $bg = "bg-success";
                    break;
                  case "cancellation":
                    $bg = "bg-danger";
                    break;
                  default:
                    null;
                }
          ?>
                <div class="col-md-6 col-lg-4 float-start my-2 ">
                  <div class="card ">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-<?= $isAdmin ? "9" : "12" ?>">
                          <span>
                            <?= ucwords($announce->announce_type) ?>
                          </span>
                          <?php if ($announce->course_id) : ?>
                            <?php
                            $course = getTableSingleDataById("course", "course_id", $announce->course_id);

                            echo "($course->acronym)";
                            ?>
                          <?php endif; ?>
                          <br>
                          <span>
                            To:
                            <?php

                            $formattedString = "";

                            if (count($notifyTo) > 1) {
                              $lastString = array_pop($notifyTo);
                              $formattedString = implode(", ", $notifyTo) . " and " . $lastString;
                            } else {
                              $formattedString = implode("", $notifyTo);
                            }

                            echo $formattedString;
                            ?>
                          </span>

                        </div>
                        <?php if ($isAdmin) : ?>
                          <div class="col-3 p-0 text-end">
                            <button type="button" onclick="window.location.href='./announcements?page=edit&&id=<?= $announce->id ?>'" class="btn btn-outline-secondary btn-rounded btn-icon m-1">
                              <i class="mdi mdi-pencil text-warning"></i>
                            </button>

                            <button onclick="deleteData('announcements', 'id', '<?= $announce->id ?>')" type="button" class="btn btn-outline-secondary btn-rounded btn-icon m-1">
                              <i class="mdi mdi-close text-danger "></i>
                            </button>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="card-body <?= $bg ?> text-white">
                      <h6 class="card-title">
                        <?= $announce->title ?>
                      </h6>
                      <p class="card-text ">
                        <?= nl2br($announce->announcement) ?>
                      </p>
                    </div>
                    <div class="card-footer">
                      Date: <?= date("F d, Y", strtotime($announce->date_created)) ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php if (!$hasAnnounce) : ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>