<?php


// return associate array data from users table 
function getUserByID($userID) {
  $query = "SELECT * FROM users WHERE id='".$userID."'";

  $result = mysql_query($query) or die(sql_error());

  if (mysql_num_rows($result) == 0) return false;

  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      //   $record = @explode("|", $data['regions']);
      $data1[$key] = urldecode($val);
    }
    $i++;
  }
  return $data1;
}

function isLoggedIn() {
  return (getUserByID($_SESSION['userid'])===false) ? false : true;
}

function getUserType($type) {
  global $MEMBERS_TYPE;
  return $MEMBERS_TYPE[$type];
}

function isUser() {
  // true is able to login
  if (isset($_SESSION['userid']) && $_SESSION['userid'] > 0) return true;
  return false;
}
function isAdministrator() {
  if (isUser()===true && $_SESSION['usertype'] == 1) return true;
  return false;
}
function isManager() {
  if (isUser()===true && ($_SESSION['usertype'] == 2 || isAdministrator()===true)) return true;
  return false;
}
function isMonitor() {
  if (isUser()===true && ($_SESSION['usertype'] == 3 || isAdministrator()===true)) return true;
  return false;
}
function isMember() {
  if (isUser()===true && ($_SESSION['usertype'] == 4 || isAdministrator()===true)) return true;
  return false;
}
function isAuthor() {
  if (isUser()===true && ($_SESSION['usertype'] == 5 || isAdministrator()===true)) return true;
  return false;
}

function selectUser($username) {

  $query = "SELECT id,username,password,mail FROM users WHERE username='".$username."'";
  $result = mysql_query($query) or die(sql_error());
  
  if (!mysql_num_rows($result)) return false;

  $record = mysql_fetch_array($result);

  return $record;
}


function createRandomPassword() {
  $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      
  $i = 0;
  $pass = '' ;
  while ($i <= 7) {
    $num = rand(0, strlen($chars));
    $tmp = substr($chars, $num, 1);
    $pass = $pass . $tmp;
    $i++;
  }
  return $pass;
}

?>