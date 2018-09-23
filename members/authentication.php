<?php
//include_once "members/common_db.php";

//mysql_query("SET NAMES 'utf8'"); 
	
//LOG OUT
if (isset($_SESSION['GET']['sel']) && $_SESSION['GET']['sel']=='logout' || $_GET['sel']=='logout') {
  session_unset();
  session_destroy();
  setcookie(preg_replace("[^A-Za-z0-9]", "", $_SERVER['HTTP_HOST']), "", time()  - 3600);
}	
//END LOG OUT

//LOG IN	
if (isset($_POST['username']) and ($_POST['username'] <> "") and isset($_POST['userpass']) and ($_POST['userpass'] <> "")) {
	
  if (!isset($link_id)) $link_id = db_connect();
  if (!$link_id) die(sql_error());
		
  $query = "SELECT * FROM users WHERE deleted = 'N' AND username='".$_POST['username']."' AND password=MD5('".$_POST['userpass']."')";
  $result = mysql_query($query) or die(sql_error());
  if (mysql_num_rows($result)) {
    $query_data = mysql_fetch_array($result);
    
    $_SESSION['userid'] = $query_data['id'];
    $_SESSION['username'] = $query_data['username'];
    $_SESSION['usertype'] = $query_data['type'];
		$_SESSION['userlang'] = $query_data['language'];
    $_SESSION['usergrupa'] = $query_data['grupa'];
    $_SESSION['usergrupa_common'] = $query_data['grupa_common'];
		
    if (!is_null($query_data['regions'])) $_SESSION['userregions'] = $query_data['regions'];
    if (!is_null($query_data['locales'])) $_SESSION['userlocales'] = $query_data['locales'];
    if (!is_null($query_data['events'])) $_SESSION['userevents'] = $query_data['events'];
    
    $query = "UPDATE users SET date_access = NOW() WHERE id = ".$query_data['id'];
    $result = mysql_query($query) or die(sql_error());
  }
}
//END LOG IN
?>