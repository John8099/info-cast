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
      case "update-user":
        updateUser();
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
      case "add_course":
        add_course();
        break;
      case "edit_course":
        edit_course();
        break;
      case "set_graduate":
        set_graduate();
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

function set_graduate()
{
  global $conn, $_POST;

  $ids = $_POST["ids"];
  $dateNow = date("Y-m-d");

  $query = mysqli_query(
    $conn,
    "UPDATE users SET `role`='alumni', set_graduate_at='$dateNow' WHERE id in(" . implode(', ', $ids) . ")"
  );

  if ($query) {
    $response["success"] = true;
    $response["message"] = "Selected students are successfully set to graduates.";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
  }

  returnResponse($response);
}

function edit_course()
{
  global $conn, $_POST;

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

    $in = update("course", $courseData, "course_id", $course_id);

    if ($in) {
      $response["success"] = true;
      $response["message"] = "Course successfully updated.";
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
  }

  returnResponse($response);
}

function add_course()
{
  global $conn, $_POST;

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
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
    }
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
      "password" => password_hash($password, PASSWORD_ARGON2I)
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
    $response["message"] = "Email already exist.<br>Please try other email.";
  }

  returnResponse($response);
}

function add_admin()
{
  global $_FILES, $_POST, $conn;

  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $email = $_POST["email"];
  $contact = $_POST["contact"];
  $password = password_hash("admin123", PASSWORD_ARGON2I);

  if (!checkEmailIfExistF("users", $email)) {
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
    } else {
      $response["success"] = false;
      $response["message"] = mysqli_error($conn);
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
    $response["message"] = "Old password doesnt match!";
  } else {
    $update = update(
      "users",
      array(
        "password" => password_hash($new, PASSWORD_ARGON2I),
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
  global $_POST, $conn;

  $table = $_POST["table"];
  $column = $_POST["column"];
  $val = $_POST["val"];

  $del = delete($table, $column, $val);

  if ($del) {
    $response["success"] = true;
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


function updateUser()
{
  global $conn, $_POST, $_FILES;

  $profile = $_FILES["profile"];
  $userId = $_POST['id'];
  $uploadedFile = "";

  if (intval($profile["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($profile['name']);
    $target_dir = "../media";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($profile['tmp_name'], "$target_dir/$uploadFile")) {
      $uploadedFile = $uploadFile;
    } else {
      $response["success"] = false;
      $response["message"] = "Error uploading profile.<br>Please try again later.";
    }
    exit();
  }

  $personalData = array(
    "first_name" => ucwords($_POST["fname"]),
    "middle_name" => ucwords($_POST["mname"]),
    "last_name" => ucwords($_POST["lname"]),
    "course_id" => $_POST["course"],
    "year" => ucwords($_POST["year"]),
    "section" => ucwords($_POST["section"]),
    "school" => ucwords($_POST["school"]),
    "place_of_birth" => ucwords($_POST["pob"]),
    "date_of_birth" => $_POST["dob"],
    "gender" => ucwords($_POST["gender"]),
    "address" => ucwords($_POST["address"]),
    "mobile_number" => $_POST["mobileNumber"],
    "email" => $_POST["email"],
    "blood_type" => ucwords($_POST["bloodType"]),
    "body_built" => ucwords($_POST["bodyBuilt"]),
    "height" => $_POST["height"],
    "weight" => $_POST["weight"],
    "ethnic_group" => ucwords($_POST["ethnicGroup"]),
    "religion" => ucwords($_POST["religion"]),
    "citizenship" => ucwords($_POST["citizenship"]),
    "identification_mark" => $_POST["identificationMark"],
    "hair_color" => ucwords($_POST["hairColor"]),
    "eye_color" => ucwords($_POST["eyeColor"]),
    "civil_status" => ucwords($_POST["civil"]),
    "avatar" => "$uploadedFile"
  );

  $updatePersonalData = update("users", $personalData, "id", $userId);
  if ($updatePersonalData) {
    // Civil Data
    if ($_POST["civil"] == "Married") {
      $civilData = array(
        "name_of_spouse" => ucwords($_POST["spouseName"]),
        "address" => ucwords($_POST["spouseAddress"]),
        "contact" => $_POST["spouseContact"],
        "occupation" => ucwords($_POST["spouseOccupation"]),
        "company_name" => ucwords($_POST["spouseCompany"])
      );

      if (isset($_POST['civil_id'])) {
        update("civil", $civilData, "user_id", $userId);
      } else {
        $civilData["user_id"] = $userId;
        insert("civil", $civilData);
      }
    } else {
      $civilDataDB = getTableData("civil", "user_id", $userId);

      if ($civilDataDB) {
        delete("civil", "civil_id", $userId);
      }
    }

    // Children Data
    if (count($_POST["childrenName"]) > 1) {

      for ($i = 0; $i < count($_POST["childrenName"]); $i++) {
        $childrenData = array(
          "name" => ucwords($_POST["childrenName"][$i]),
          "date_of_birth" => $_POST["childrenDOB"][$i],
          "place_birth" => $_POST["childrenPOB"][$i],
          "grade_or_year" => $_POST["childrenGradeOrYearLevel"][$i],
          "school" => ucwords($_POST["childrenSchool"][$i])
        );

        if ($_POST["childrenID"][$i] != "0") {
          update("childrens", $childrenData, "children_id", $_POST["childrenID"][$i]);
        } else {
          $childrenData["user_id"] = $userId;
          insert("childrens", $childrenData);
        }
      }
    } else {
      $childrenData = array(
        "name" => ucwords($_POST["childrenName"][0]),
        "date_of_birth" => $_POST["childrenDOB"][0],
        "place_birth" => $_POST["childrenPOB"][0],
        "grade_or_year" => $_POST["childrenGradeOrYearLevel"][0],
        "school" => ucwords($_POST["childrenSchool"][0])
      );

      if ($_POST["childrenID"][0] != "0") {
        update("childrens", $childrenData, "children_id", $_POST["childrenID"][0]);
      } else {
        $childrenData["user_id"] = $userId;
        insert("childrens", $childrenData);
      }
    }

    // Family Data
    $familyData = array(
      "father_name" => ucwords($_POST["fatherName"]),
      "father_date_of_birth" => $_POST["fatherDOB"],
      "father_place_of_birth" => ucwords($_POST["fatherPOB"]),
      "father_address" => ucwords($_POST["fatherAddress"]),
      "father_contact" => $_POST["fatherContact"],
      "father_occupation" => ucwords($_POST["fatherOccupation"]),
      "father_company_name" => ucwords($_POST["fatherCompany"]),
      "mother_name" => ucwords($_POST["motherName"]),
      "mother_date_of_birth" => $_POST["motherDOB"],
      "mother_place_of_birth" => ucwords($_POST["motherPOB"]),
      "mother_address" => ucwords($_POST["motherAddress"]),
      "mother_contact" => $_POST["motherContact"],
      "mother_occupation" => ucwords($_POST["motherOccupation"]),
      "mother_company_name" => ucwords($_POST["motherCompany"]),
    );

    update("family", $familyData, "user_id", $userId);

    // Siblings Data
    if (count($_POST["siblingName"]) > 1) {
      for ($i = 0; $i < count($_POST["siblingName"]); $i++) {
        $siblingData = array(
          "name" => ucwords($_POST["siblingName"][$i]),
          "date_of_birth" => $_POST["siblingDOB"][$i],
          "occupation" => ucwords($_POST["siblingOccupation"][$i]),
          "company" => ucwords($_POST["siblingCompany"][$i]),
        );

        if ($_POST["siblingID"][$i] != "0") {
          update("siblings", $siblingData, "sibling_id", $_POST["siblingID"][$i]);
        } else {
          $siblingData["user_id"] = $userId;
          insert("siblings", $siblingData);
        }
      }
    } else {
      $siblingData = array(
        "name" => ucwords($_POST["siblingName"][0]),
        "date_of_birth" => $_POST["siblingDOB"][0],
        "occupation" => ucwords($_POST["siblingOccupation"][0]),
        "company" => ucwords($_POST["siblingCompany"][0]),
      );

      if ($_POST["siblingID"][0] != "0") {
        update("siblings", $siblingData, "sibling_id", $_POST["siblingID"][0]);
      } else {
        $siblingData["user_id"] = $userId;
        insert("siblings", $siblingData);
      }
    }

    // Education Data
    if (count($_POST["educationLevel"]) > 1) {
      for ($i = 0; $i < count($_POST["educationLevel"]); $i++) {
        $educationData = array(
          "education_level" => ucwords($_POST["educationLevel"][$i]),
          "course_taken" => ucwords($_POST["educationCourse"][$i]),
          "name_of_school" => ucwords($_POST["educationSchoolName"][$i]),
          "address" => ucwords($_POST["educationAddress"][$i]),
          "year_completed" => $_POST["yearCompleted"][$i],
        );

        if ($_POST["educationID"][$i] != "0") {
          update("education", $educationData, "education_id", $_POST["educationID"][$i]);
        } else {
          $educationData["user_id"] = $userId;
          insert("education", $educationData);
        }
      }
    } else {
      $educationData = array(
        "education_level" => ucwords($_POST["educationLevel"][0]),
        "course_taken" => ucwords($_POST["educationCourse"][0]),
        "name_of_school" => ucwords($_POST["educationSchoolName"][0]),
        "address" => ucwords($_POST["educationAddress"][0]),
        "year_completed" => $_POST["yearCompleted"][0],
      );

      if ($_POST["educationID"][0] != "0") {
        update("education", $educationData, "education_id", $_POST["educationID"][0]);
      } else {
        $educationData["user_id"] = $userId;
        insert("education", $educationData);
      }
    }

    $response["success"] = true;
    $response["message"] = "User has been updated successfully";
  } else {
    $response["success"] = false;
    $response["message"] = mysqli_error($conn);
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
