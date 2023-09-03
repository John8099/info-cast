<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

date_default_timezone_set("Asia/Manila");

include("conn.php");
include("helpers.php");

$response = array(
  "success" => false,
  "message" => ""
);

$user = null;
$isLogin = isset($_SESSION["userId"]) ? true : false;
if ($isLogin) {
  $user = getUserById($_SESSION["userId"]);
}

if (isset($_GET['action'])) {
  try {
    switch ($_GET['action']) {
      case "logout":
        logout();
        break;
      case "login":
        login();
        break;
      case "update_user":
        update_user();
        break;
      case "check_email":
        checkEmailIfExistR();
        break;
      case "delete_item":
        deleteItem();
        break;
      case "change_password":
        change_password();
        break;
      case "add_admin":
        add_admin();
        break;
      case "create_student_account":
        create_student_account();
        break;
      case "create_staff_account":
        create_staff_account();
        break;
      case "add_course":
        add_course();
        break;
      case "edit_course":
        edit_course();
        break;
      case "set_alumni":
        set_alumni();
        break;
      case "update_settings":
        update_settings();
        break;
      case "verify_student":
        verify_student();
        break;
      case "verify_account":
        verify_account();
        break;
      case "new_announcement":
        new_announcement();
        break;
      case "edit_announcement":
        edit_announcement();
        break;
      default:
        null;
        break;
    }
  } catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
  }
}

function edit_announcement()
{
  global $conn, $_POST, $_SESSION;

  $id = $_POST["id"];

  $title = $_POST["title"];
  $announce_type = $_POST["announce_type"];
  $notify_to = count($_POST["notify_to"]) > 1 ? implode(", ", $_POST["notify_to"]) : $_POST["notify_to"][0];
  $course_id = isset($_POST["course_id"]) ? $_POST["course_id"] : null;
  $announcement = $_POST["announcement"];

  $announceData = array(
    "course_id" => $course_id,
    "title" => $title,
    "announce_type" => $announce_type,
    "notified_to" => $notify_to,
    "announcement" => mysqli_escape_string($conn, nl2br($announcement))
  );

  $notification_content = "Edited <strong>announcement</strong>.";
  // SMS Notif sms(announcements)  
  $qStr = "";

  if (count($_POST["notify_to"]) > 1) {
    $inStr = array();
    foreach ($_POST["notify_to"] as $n) {
      array_push($inStr, "\"$n\"");
    }

    $qStr = "role IN (" . implode(',', $inStr) . ")";
  } else {
    $qStr = "role = '$notify_to'";
  }

  $toNotifyData = getTableWithWhere("users", $qStr);
  $message = "$title: $announcement";

  foreach ($toNotifyData as $data) {
    if ($data->course_id == $course_id || $data->course_id == null) {
      $contact = $data->contact;

      $announceTxt = ($title . ": ");
      $announceTxt .= $announcement;

      add_notification($_SESSION["userId"], $data->id, $notification_content);
      if ($contact) {
        if (strlen($contact) == 11) {
          sendSms($contact, $message);
        }
      }
    }
  }

  $announceIn = update("announcements", $announceData, "id", $id);

  if ($announceIn) {
    insertActivity($notification_content, $_SESSION["userId"]);
    $response["success"] = true;
    $response["message"] = "Announcement successfully edited and broadcast.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function new_announcement()
{
  global $conn, $_POST, $_SESSION;

  $title = $_POST["title"];
  $announce_type = $_POST["announce_type"];
  $notify_to = count($_POST["notify_to"]) > 1 ? implode(", ", $_POST["notify_to"]) : $_POST["notify_to"][0];
  $course_id = isset($_POST["course_id"]) ? $_POST["course_id"] : null;
  $announcement = $_POST["announcement"];

  $announceData = array(
    "course_id" => $course_id,
    "title" => $title,
    "announce_type" => $announce_type,
    "notified_to" => $notify_to,
    "announcement" => mysqli_escape_string($conn, nl2br($announcement))
  );
  $notification_content = "Posted <strong>New Announcement</strong>";

  // SMS Notif sms(announcements)
  $qStr = "";

  if (count($_POST["notify_to"]) > 1) {
    $inStr = array();
    foreach ($_POST["notify_to"] as $n) {
      array_push($inStr, "\"$n\"");
    }

    $qStr = "role IN (" . implode(',', $inStr) . ")";
  } else {
    $qStr = "role = '$notify_to'";
  }

  $toNotifyData = getTableWithWhere("users", $qStr);

  $message = "$title: $announcement";
  foreach ($toNotifyData as $data) {
    if ($data->course_id == $course_id || $data->course_id == null) {
      $contact = $data->contact;

      add_notification($_SESSION["userId"], $data->id, $notification_content);
      if ($contact) {
        if (strlen($contact) == 11) {
          sendSms($contact, $message);
        }
      }
    }
  }

  $announceIn = insert("announcements", $announceData);

  if ($announceIn) {
    insertActivity("Posted <strong>New Announcement</strong>", $_SESSION["userId"]);
    $response["success"] = true;
    $response["message"] = "Announcement successfully broadcast.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function verify_account()
{
  global $conn, $_POST, $_FILES;

  $student_id = $_POST["id"];

  $verification_img = null;

  if (isset($_FILES["img"])) {
    $uploadedFile = uploadImg($_FILES["img"], "../media/verification");
    $verification_img = $uploadedFile->success ? $uploadedFile->file_name : null;
  }

  if ($verification_img) {
    $verificationData = array(
      "is_verified" => "1",
      "verification_img" => $verification_img
    );

    $update = update("users", $verificationData, "id", $student_id);

    if ($update) {
      $response["success"] = true;
      $response["message"] = "Successfully submitted verification.<br>Please wait for the admin to review your verification.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  } else {
    if ($_FILES["img"]["error"] == 4) {
      $response["success"] = false;
      $response["message"] = "No file was uploaded.<br>Please upload your School ID to verify your account.";
    } else {
      $response["success"] = false;
      $response["message"] = "Error uploading verification image.<br>Please try again later.";
    }
  }

  returnResponse($response);
}

function verify_student()
{
  global $conn, $_POST, $_SESSION;

  $student_id = $_POST["student_id"];
  $action = $_POST["action"];

  $studentVerificationData = array(
    "is_verified" => $action == "approve" ? "2" : "3"
  );

  $update = update("users", $studentVerificationData, "id", $student_id);

  if ($update) {
    $response["success"] = true;
    $response["message"] = ("Successfully $action" . "d student verification.");

    $notification_content = ("Your account verification was $action" . "d by the Admin.");
    add_notification($_SESSION["userId"], $student_id, $notification_content);

    $studentName = getFullName($student_id, "with_email");
    $activityAction = ucwords($action);

    insertActivity("<strong>$activityAction</strong> '$studentName' verification.", $_SESSION["userId"]);
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function add_notification($created_by, $user_id, $content)
{
  $insertData = array(
    "created_by" => $created_by,
    "user_id" => $user_id,
    "content" => $content
  );

  insert("notification", $insertData);
}

function verificationImg($userId)
{
  global $SERVER_NAME;
  $user = getUserById($userId);

  if ($user->verification_img) {
    $alt = preg_split("/\d+-\d+_/", $user->verification_img)[1];
    return "<img onclick='handleModalOpen($(this))' class='modalImg' src='$SERVER_NAME/media/verification/$user->verification_img' alt='$alt' style='object-fit: cover;'>";
  }

  return '<span class="badge badge-danger rounded-pill px-2" style="font-size: 14px">
  No Verification Image
</span>';
}

function insertActivity($action, $userId)
{
  $insertData = array(
    "user_id" => $userId,
    "action" => $action
  );

  insert("activity", $insertData);
}

function update_settings()
{
  global $conn, $_POST, $_SESSION;

  $teacher_reg = $_POST["teacher_reg"] == "true" ? "1" : "0";

  $updateSet = mysqli_query(
    $conn,
    "UPDATE settings SET teacher_reg='$teacher_reg' "
  );

  if ($updateSet) {
    $response["success"] = true;
    $action = $teacher_reg == "1" ? "ON" : "OFF";
    insertActivity("<strong>Turned $action</strong> Teacher registration.", $_SESSION["userId"]);
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function set_alumni()
{
  global $conn, $_POST, $_SESSION;

  $ids = $_POST["ids"];
  $dateNow = date("Y-m-d");

  $query = mysqli_query(
    $conn,
    "UPDATE users SET `role`='alumni', set_graduate_at='$dateNow' WHERE id in(" . implode(', ', $ids) . ")"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Selected students are successfully set to alumni.";

    $names = "";
    foreach ($ids as $id) {
      $names .= ("'" . getFullName($id, "with_middle") . "'");
    }

    insertActivity("Set [$names] to alumni", $_SESSION["userId"]);
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function edit_course()
{
  global $conn, $_POST, $_SESSION;

  $course_id = $_POST["course_id"];
  $name = ucwords($_POST["name"]);
  $acronym = $_POST["acronym"];
  $active = isset($_POST["active"]) ? "active" : "inactive";

  $checkCourse = getTableWithWhere("course", "name LIKE '%$name%' AND acronym LIKE '%$acronym%' and course_id <> $course_id");

  if (count($checkCourse) > 0) {
    $response["success"] = false;
    $response["message"] = "Course already exist";
  } else {
    $courseData = array(
      "name" => $name,
      "acronym" => $acronym,
      "status" => $active,
    );
    $courseDb = getTableData("course", "course_id", $course_id);
    $in = update("course", $courseData, "course_id", $course_id);

    if ($in) {
      $response["success"] = true;
      $response["message"] = "Course successfully updated.";

      $courseName = $courseDb[0]->name;
      $courseAcronym = $courseDb[0]->acronym;

      $newActive = ucwords($active);
      $oldActive = ucwords($courseDb[0]->status);

      insertActivity(("<strong>Edited Course: </strong> '($courseAcronym) $courseName' to '($acronym) $name' <strong>Status:</strong> '$oldActive' to '$newActive'."), $_SESSION["userId"]);
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  }

  returnResponse($response);
}

function add_course()
{
  global $conn, $_POST, $_SESSION;

  $name = ucwords($_POST["name"]);
  $acronym = $_POST["acronym"];
  $active = isset($_POST["active"]) ? "active" : "inactive";

  $checkCourse = getTableWithWhere("course", "name LIKE '%$name%' AND acronym LIKE '%$acronym%'");

  if (count($checkCourse) > 0) {
    $response["success"] = false;
    $response["message"] = "Course already exist";
  } else {
    $courseData = array(
      "name" => $name,
      "acronym" => $acronym,
      "status" => $active,
    );

    $in = insert("course", $courseData);

    if ($in) {
      $response["success"] = true;
      $response["message"] = "Course successfully added.";
      $stat = ucwords($active);
      insertActivity(("<strong>New Course:</strong> '($acronym) $name' <strong>Status:</strong> $stat."), $_SESSION["userId"]);
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  }

  returnResponse($response);
}

function create_staff_account()
{
  global $conn, $_FILES, $_POST;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $course_id = isset($_POST["course_id"]) ? $_POST["course_id"] : null;
  $contact = $_POST["contact"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  if (!checkEmailIfExistF("users", $email)) {

    if (strlen($contact) == 11) {
      $avatar = null;
      if (isset($_FILES["img"])) {
        $uploadedFile = uploadImg($_FILES["img"], "../media");
        $avatar = $uploadedFile->success ? $uploadedFile->file_name : null;
      }

      $insertData = array(
        "fname" => ucwords($fname),
        "mname" => ucwords($mname),
        "lname" => ucwords($lname),
        "course_id" => $course_id,
        "email" => $email,
        "contact" => $contact,
        "role" => $course_id ? "teacher" : "staff",
        "avatar" => $avatar,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "is_verified" => "1"
      );

      $insert = insert("users", $insertData);

      if ($insert) {
        $response["success"] = true;
        $response["message"] = "Your account is successfully created.<br>You can now login to your account.";
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Contact number should be 11 digits.<br>Your entered (" . strlen($contact) . ") only.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist.<br>Please try other email.";
  }

  returnResponse($response);
}

function create_student_account()
{
  global $conn, $_FILES, $_POST;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $course_id = $_POST["course_id"];
  $sy = $_POST["sy"];
  $year = $_POST["year"];
  $section = $_POST["section"];
  $contact = $_POST["contact"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  if (!checkEmailIfExistF("users", $email)) {

    if (strlen($contact) == 11) {
      $avatar = null;
      if (isset($_FILES["img"])) {
        $uploadedFile = uploadImg($_FILES["img"], "../media");
        $avatar = $uploadedFile->success ? $uploadedFile->file_name : null;
      }

      $insertData = array(
        "fname" => ucwords($fname),
        "mname" => ucwords($mname),
        "lname" => ucwords($lname),
        "course_id" => $course_id,
        "year" => $year,
        "section" => strtoupper($section),
        "sy" => $sy,
        "email" => $email,
        "contact" => $contact,
        "role" => "student",
        "avatar" => $avatar,
        "password" => password_hash($password, PASSWORD_DEFAULT),
        "is_verified" => "set_zero"
      );

      $insert = insert("users", $insertData);

      if ($insert) {
        $response["success"] = true;
        $response["message"] = "Your account is successfully created.<br>You can now login to your account.";
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Contact number should be 11 digits.<br>Your entered (" . strlen($contact) . ") only.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist.<br>Please try other email.";
  }

  returnResponse($response);
}

function add_admin()
{
  global $_FILES, $_POST, $conn, $_SESSION;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $contact = $_POST["contact"];
  $password = password_hash("admin123", PASSWORD_DEFAULT);

  if (!checkEmailIfExistF("users", $email)) {
    if (strlen($contact) == 11) {
      $uploadedFile = uploadImg($_FILES["img"], "../media");
      $avatar = $uploadedFile->success ? $uploadedFile->file_name : null;

      $insertData = array(
        "fname" => ucwords($fname),
        "mname" => ucwords($mname),
        "lname" => ucwords($lname),
        "email" => $email,
        "contact" => $contact,
        "role" => "admin",
        "avatar" => $avatar,
        "password" => $password,
        "isNew" => "1"
      );

      $insert = insert("users", $insertData);

      if ($insert) {
        $response["success"] = true;
        $response["message"] = "Admin successfully added.<br>The default password is <strong>\"admin123\"</strong>";
        insertActivity(("<strong>Added new admin:</strong> '" . getFullName($insert, "with_middle") . "'."), $_SESSION["userId"]);
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Contact number should be 11 digits.<br>Your entered (" . strlen($contact) . ") only.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist.<br>Please try other email.";
  }

  returnResponse($response);
}

function change_password()
{
  global $conn, $_POST, $_SESSION;

  $userId = $_SESSION["userId"];
  $old = $_POST["old"];
  $new = $_POST["new"];

  $user = getUserById($userId);

  if ($old == $new) {
    $response["success"] = false;
    $response["message"] = "Old password and New password should not be the same.";
  } else if (!password_verify($old, $user->password)) {
    $response["success"] = false;
    $response["message"] = "Old password does not match!";
  } else {
    $update = update(
      "users",
      array(
        "password" => password_hash($new, PASSWORD_DEFAULT),
        "isNew" => "set_null"
      ),
      "id",
      $userId
    );

    if ($update) {
      $response["success"] = true;
      $response["userId"] = $userId;
      $response["message"] = "Password successfully change";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  }

  returnResponse($response);
}

function deleteItem()
{
  global $_POST, $conn, $_SESSION;

  $table = $_POST["table"];
  $column = $_POST["column"];
  $val = $_POST["val"];

  $activity = "";
  switch ($table) {
    case "course":
      $getCourseData = getTableData($table, $column, $val);
      $courseData = $getCourseData[0];
      $activity = "<strong>Deleted course:</strong> '($courseData->acronym) $courseData->name'.";
      break;
    default:
      null;
  }

  $del = delete($table, $column, $val);

  if ($del) {
    $response["success"] = true;
    if ($activity) {
      insertActivity($activity, $_SESSION["userId"]);
    }
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function checkEmailIfExistR()
{
  global $conn, $_POST;

  $id = isset($_GET['id']) ? $_GET['id'] : null;

  returnResponse(
    ["isExist" => mysqli_num_rows(
      mysqli_query(
        $conn,
        "SELECT * FROM users WHERE " . ($id ? "id != '$id' and " : "") . " email = '{$_POST['email']}'"
      )
    ) > 0 ? true : false]
  );
}


function update_user()
{
  global $conn, $_FILES, $_POST;

  $userId = $_POST["id"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $contact = $_POST["contact"];
  $email = $_POST["email"];

  if (!checkEmailIfExistF("users", $email, $userId)) {
    if (strlen($contact) == 11) {
      $avatar = null;
      if (isset($_FILES["img"])) {
        if ($_POST["imgIsCleared"] == "yes") {
          $avatar = "set_null";
        } else {
          $uploadedFile = uploadImg($_FILES["img"], "../media");
          $avatar = $uploadedFile->success ? $uploadedFile->file_name : null;
        }
      }

      if ($_POST["role"] == "admin" || $_POST["role"] == "staff") {
        $updateData = array(
          "fname" => ucwords($fname),
          "mname" => ucwords($mname),
          "lname" => ucwords($lname),
          "email" => $email,
          "contact" => $contact,
          "avatar" => $avatar,
        );
      } else {
        $course_id = $_POST["course_id"];

        if ($_POST["role"] == "teacher") {
          $updateData = array(
            "fname" => ucwords($fname),
            "mname" => ucwords($mname),
            "lname" => ucwords($lname),
            "course_id" => $course_id,
            "email" => $email,
            "contact" => $contact,
            "role" => "teacher",
            "avatar" => $avatar,
          );
        } else {
          // Role Student
          $sy = $_POST["sy"];
          $year = $_POST["year"];
          $section = $_POST["section"];

          $updateData = array(
            "fname" => ucwords($fname),
            "mname" => ucwords($mname),
            "lname" => ucwords($lname),
            "course_id" => $course_id,
            "year" => $year,
            "section" => strtoupper($section),
            "sy" => $sy,
            "email" => $email,
            "contact" => $contact,
            "role" => "student",
            "avatar" => $avatar,
          );
        }
      }

      $update = update("users", $updateData, "id", $userId);

      if ($update) {
        $response["success"] = true;
        $response["message"] = "Profile successfully updated";
      } else {
        $response["success"] = false;
        $response["message"] = mysqli_error($conn);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Contact number should be 11 digits.<br>Your entered (" . strlen($contact) . ") only.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "Email already exist.<br>Please try other email.";
  }

  returnResponse($response);
}

function login()
{
  global $conn;

  $email = $_POST["email"];
  $password = $_POST["password"];

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE email='$email'"
  );

  if (mysqli_num_rows($query) > 0) {

    $user = mysqli_fetch_object($query);

    if (password_verify($password, $user->password)) {
      $response["success"] = true;
      $_SESSION["userId"] = $user->id;
      $response["role"] = $user->role;

      if ($user->role == "admin") {
        $response["isNew"] = $user->isNew;
        insertActivity("'" . getFullName($user->id, "with_email") . "' <strong>Logged in</strong>.", $user->id);
      }
    } else {
      $response["success"] = false;
      $response["message"] = "Password not match.";
    }
  } else {
    $response["success"] = false;
    $response["message"] = "User not found.";
  }


  returnResponse($response);
}

function logout()
{
  global $_SESSION;
  insertActivity("'" . getFullName($_SESSION['userId'], "with_email") . "' <strong>Logged out</strong>.", $_SESSION['userId']);
  $_SESSION = array();

  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  session_destroy();
  header("location: ../");
}
