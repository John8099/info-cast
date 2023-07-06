<?php

$dateNow = date("Y-m-d H:i:s");

$separator = "!I_I!";

$ORIGIN = "http://$_SERVER[SERVER_NAME]";
$PATH = ("/" . explode("/", $_SERVER["REQUEST_URI"])[1]);

$divDisplayId = 'display';
$divBrowseId = 'browse';
$divClearId = 'clear';
$inputFileId = 'formInput';
$inputFileName = 'img';

$SERVER_NAME = "";
if ($_SERVER['HTTP_HOST'] == "localhost") {
  $SERVER_NAME = ($ORIGIN . $PATH);
} else {
  $SERVER_NAME = ($ORIGIN);
}


function checkEmailIfExistF($table, $email, $id = null)
{
  global $conn;

  return mysqli_num_rows(
    mysqli_query(
      $conn,
      "SELECT * FROM `$table` WHERE " . ($id ? "id != '$id' and " : "") . " email = '{$email}'"
    )
  ) > 0 ? true : false;
}

function uploadImg($file, $path)
{
  $res = array(
    "success" => false,
    "file_name" => ""
  );

  if (intval($file["error"]) == 0) {
    $uploadFile = date("mdY-his") . "_" . basename($file['name']);

    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], "$path/$uploadFile")) {
      $res["success"] = true;
      $res["file_name"] = $uploadFile;
    }
  }
  return (object) $res;
}

function getTableData($table, $column = null, $value = null)
{
  global $conn;

  $data = array();

  $query = mysqli_query(
    $conn,
    "SELECT * FROM $table " . ($column ? "WHERE $column='$value'" : "")
  );

  while ($row = mysqli_fetch_object($query)) {
    array_push($data, $row);
  }

  return $data;
}

function getTableSingleDataById($table, $columnId, $value)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM $table WHERE $columnId='$value' "
  );

  return mysqli_num_rows($query) > 0 ? mysqli_fetch_object($query) : null;
}

function update($table, $data, $columnWHere, $columnVal)
{

  global $conn;

  $set = array();

  try {
    if (count($data) > 0) {
      foreach ($data as $column => $value) {
        if ($value) {
          array_push($set, "$column = '" . mysqli_escape_string($conn, $value) . "'");
        }

        if ($value == "set_null") {
          array_push($set, "$column = NULL");
        }
      }

      if (count($set) > 0) {
        $queryStr = "UPDATE `$table` SET " . (implode(', ', $set)) . " WHERE $columnWHere='$columnVal'";
        $query = mysqli_query($conn, $queryStr);
        $err = mysqli_error($conn);

        return $query;
      }

      return null;
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function delete($table, $column, $value)
{
  global $conn;

  try {
    $queryStr = "DELETE FROM `$table` WHERE `$column`='$value'";

    return mysqli_query($conn, $queryStr);
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function insert($table, $data)
{
  global $conn;

  $columns = array();
  $values = array();

  try {
    if (count($data) > 0) {
      foreach ($data as $column => $value) {
        if ($value) {
          array_push($columns, "`$column`");
          array_push($values, "'" . mysqli_escape_string($conn, $value) . "'");
        }
      }

      if (count($values) == count($columns)) {
        $queryStr = "INSERT INTO `$table` (" . implode(",", $columns) . ") VALUES (" . implode(",", $values) . ")";

        $query = mysqli_query($conn, $queryStr);

        if ($query) {
          return mysqli_insert_id($conn);
        } else {
          $error = mysqli_error($conn);
        }
      }

      return null;
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }

  return null;
}

function generateSystemId($table, $primaryStr, $preferredLetter = null)
{
  global $conn, $db;
  $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

  $AUTO_INCREMENT = mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SELECT AUTO_INCREMENT AS ID FROM information_schema.tables WHERE table_name = '$table' and table_schema = '$db'"
    )
  );

  $countUser = mysqli_num_rows(
    mysqli_query(
      $conn,
      "SELECT COUNT(*) AS count FROM `$table`"
    )
  );

  $letterIndex = intval(intval($countUser) / 100);
  $letter = $preferredLetter == null ? $characters[$letterIndex] : $preferredLetter;

  return $primaryStr . date('y') . $letter . str_pad($AUTO_INCREMENT->ID, 4, '0', STR_PAD_LEFT);
}

function isSelected($value, $toCheck)
{
  if ($value && $toCheck) {
    if ($value == $toCheck) {
      return "selected";
    } else {
      return "";
    }
  }
  return "";
}

function getUserById($userId)
{
  global $conn;

  $query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$userId'"
  );

  return mysqli_num_rows($query) > 0 ? mysqli_fetch_object($query) : null;
}

function getFullName($userId, $format = "") // format = with_middle
{
  $user = getUserById($userId);
  $fullName = "";

  if ($user->mname == "") {
    $fullName = ucwords("$user->fname $user->lname");
  } else {
    if ($format) {
      $fullName = ucwords("$user->fname $user->mname $user->lname");
    } else {
      $middle = $user->mname[0];
      $fullName = ucwords("$user->fname " . $middle . ". $user->lname");
    }
  }

  return $fullName;
}

function generateImgUpload($userId = null, $divDisplayId = 'display', $divBrowseId = 'browse', $divClearId = 'clear', $inputFileId = 'formInput', $inputFileName = 'img')
{
  return ("
  <div class=\"col-md-12 mb-4\">
    <div class=\"form-group\">
      <img src=\"" . (getAvatar($userId)) . "\" class=\"rounded mx-auto d-block\" style=\"width: 150px; height: 150px;\" id=\"" . ($divDisplayId) . "\">
    </div>
    <div class=\"mt-3\" style=\"display: flex; justify-content: center;\" id=\"" . ($divBrowseId) . "\">
      <button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"return changeImage('#$inputFileId')\">
        Browse
      </button>
    </div>
    <div class=\"mt-3\" style=\"display: flex; justify-content: center; \" id=\"" . ($divClearId) . "\">
      <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"return clearImg('#$divDisplayId', '#$divClearId', '#$divBrowseId')\">
        Clear
      </button>
    </div>
    <div class=\"mt-3\" style=\"display: none;\">
      <input class=\"form-control form-control-sm\" type=\"file\" accept=\"image/*\" onchange=\"return previewFile(this, '#$divDisplayId', '#$divClearId', '#$divBrowseId')\" id=\"" . ($inputFileId) . "\" name=\"" . ($inputFileName) . "\">
    </div>
  </div>
  ");
}

function getAvatar($userId = null)
{
  global $SERVER_NAME;
  if ($userId) {
    $user = getUserById($userId);

    if ($user->avatar) {
      return "$SERVER_NAME/media/$user->avatar";
    }
  }

  return "$SERVER_NAME/public/default.png";
}

function returnResponse($params)
{
  print_r(
    json_encode($params)
  );
}

function pr($data)
{
  echo "<pre>";
  print_r($data); // or var_dump($data);
  echo "</pre>";
}
