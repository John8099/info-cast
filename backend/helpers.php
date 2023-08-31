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
$inputIsClearedId = "isCleared";
$inputIsClearedName = "imgIsCleared";

$SERVER_NAME = "";

function sendSms($c_number)
{
  require_once(__DIR__ . '/vendor/autoload.php');

  // Configure HTTP basic authorization: BasicAuth
  $config = ClickSend\Configuration::getDefaultConfiguration()
    ->setUsername('dummywhat44@gmail.com')
    ->setPassword('Dummy44what!');

  $apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(), $config);
  $msg = new \ClickSend\Model\SmsMessage();
  $msg->setBody("This is send through info cast system");
  $msg->setTo($c_number);
  $msg->setSource("sdk");

  // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
  $sms_messages = new \ClickSend\Model\SmsMessageCollection();
  $sms_messages->setMessages([$msg]);

  try {
    $result = $apiInstance->smsSendPost($sms_messages);
    returnResponse($result);
  } catch (Exception $e) {
    echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
  }
  // $curl = curl_init();

  // curl_setopt_array($curl, [
  //   CURLOPT_URL => "https://control.msg91.com/api/v5/flow/",
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => "",
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 30,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => "POST",
  //   CURLOPT_POSTFIELDS => json_encode([
  //     'template_id' => 'EntertemplateID',
  //     'short_url' => '1 (On) or 0 (Off)',
  //     'recipients' => [
  //       [
  //         'mobiles' => '9279172745',
  //         'VAR1' => 'VALUE1',
  //         'VAR2' => 'VALUE2'
  //       ]
  //     ]
  //   ]),
  //   CURLOPT_HTTPHEADER => [
  //     "accept: application/json",
  //     "authkey: 404831ArEMazTTyd64f08589P1",
  //     "content-type: application/json"
  //   ],
  // ]);

  // $response = curl_exec($curl);
  // $err = curl_error($curl);

  // curl_close($curl);

  // if ($err) {
  //   echo "cURL Error #:" . $err;
  // } else {
  //   echo $response;
  // }
}

function get_enum_values($table, $field)
{
  global $conn;

  $type = mysqli_fetch_object(
    mysqli_query(
      $conn,
      "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'"
    )
  )->Type;

  preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
  $enum = explode("','", $matches[1]);
  sort($enum);
  return $enum;
}

if (inWhiteList($_SERVER['REMOTE_ADDR'])) {
  $SERVER_NAME = ($ORIGIN . $PATH);
} else {
  $SERVER_NAME = ($ORIGIN);
}

function generateModalImg($modalId, $modalImg, $captionId)
{
  return "
    <div id='$modalId' class='div-modal pt-5'>
      <span class='close' onclick='handleClose(`$modalId`, `$modalImg`, `$captionId`)'>&times;</span>
      <img class='div-modal-content' id='$modalImg'>
      <div id='$captionId'></div>
    </div>
  ";
}

function getTableWithWhere($table, $condition = null)
{
  global $conn;

  $data = array();

  $cond = $condition ? " WHERE $condition" : "";

  $query = mysqli_query(
    $conn,
    "SELECT * FROM $table $cond"
  );

  while ($res = mysqli_fetch_object($query)) {
    array_push($data, $res);
  }

  return $data;
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
          if ($value == "set_null") {
            array_push($set, "$column = NULL");
          }
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
          if ($value == "set_zero") {
            array_push($values, "'0'");
          } else {
            array_push($values, "'" . mysqli_escape_string($conn, $value) . "'");
          }
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

function generateImgUpload(
  $userId = null,
  $imgPath = null,
  $divDisplayId = 'display',
  $divBrowseId = 'browse',
  $divClearId = 'clear',
  $inputFileId = 'formInput',
  $inputFileName = 'img',
  $inputIsClearedId = "isCleared",
  $inputIsClearedName = "imgIsCleared"
) {
  /**
   *  N O T E
   *  Is cleared input can be applicable with update user only
   */

  $imgSrc = "";
  if ($imgPath) {
    $imgSrc = getAvatar($userId, $imgPath);
  } else {
    $imgSrc = getAvatar($userId);
  }
  $explodedImgSrc = explode("/", $imgSrc);
  $isDefaultImg = $explodedImgSrc[count($explodedImgSrc) - 2] == "public" ? true : false;

  $hideBrowseButtonLogic = $isDefaultImg ? "flex" : "none";
  $hideClearButtonLogic = $isDefaultImg ? "none" : "flex";

  return ("
  <div class=\"col-md-12 mb-4\">
    <div class=\"form-group\">
      <img src=\"" . ($imgSrc) . "\" class=\"rounded mx-auto d-block\" style=\"width: 150px; height: 150px;\" id=\"" . ($divDisplayId) . "\">
    </div>
    <div class=\"mt-3 d-$hideBrowseButtonLogic justify-content-center\" id=\"" . ($divBrowseId) . "\">
      <button type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"return changeImage('#$inputFileId', '#$inputIsClearedId')\">
        Browse
      </button>
    </div>
    <div class=\"mt-3 d-$hideClearButtonLogic justify-content-center\"  id=\"" . ($divClearId) . "\">
      <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"return clearImg('#$divDisplayId', '#$divClearId', '#$divBrowseId', '#$inputIsClearedId', '$imgPath')\">
        Clear
      </button>
    </div>
    <div class=\"mt-3\" style=\"display: none;\">
      <input class=\"form-control form-control-sm\" type=\"file\" accept=\"image/*\" onchange=\"return previewFile(this, '#$divDisplayId', '#$divClearId', '#$divBrowseId')\" id=\"" . ($inputFileId) . "\" name=\"" . ($inputFileName) . "\">
    </div>

    <input type=\"text\" value=\"no\" name=\"$inputIsClearedName\" id=\"$inputIsClearedId\" hidden>
  </div>
  ");
}

function getAvatar($userId = null, $imgPath = null)
{
  global $SERVER_NAME;
  if ($userId) {
    $user = getUserById($userId);

    if ($user->avatar) {
      return "$SERVER_NAME/media/$user->avatar";
    }
  }

  return $imgPath ? $imgPath : "$SERVER_NAME/public/default.png";
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

function get_time_ago($time)
{
  $time_difference = time() - $time;

  if ($time_difference < 1) {
    return 'less than 1 second ago';
  }
  $condition = array(
    12 * 30 * 24 * 60 * 60 =>  'year',
    30 * 24 * 60 * 60       =>  'month',
    24 * 60 * 60            =>  'day',
    60 * 60                 =>  'hour',
    60                      =>  'minute',
    1                       =>  'second'
  );

  foreach ($condition as $secs => $str) {
    $d = $time_difference / $secs;

    if ($d >= 1) {
      $t = round($d);
      return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
    }
  }
}

function is_connected()
{
  $connected = @fsockopen("www.example.com", 80);
  //website, port  (try 80 or 443)
  if ($connected) {
    $is_conn = true; //action when connected
    fclose($connected);
  } else {
    $is_conn = false; //action in connection failure
  }
  return $is_conn;
}

function inWhiteList($val)
{
  $whitelist = array(
    '127.0.0.1',
    '::1',
    '192.168'
  );

  $inList = false;

  foreach ($whitelist as $ip) {
    if (str_contains($val, $ip)) {
      $inList = true;
      break;
    }
  }

  return $inList;
}
