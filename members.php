<?php
/*
 * include files
 */	
// connect and authenticate
include_once "members/common_db.php";
include_once "members/authentication.php";

//check the connection
if (!isset($link_id)) $link_id = db_connect();
if (!$link_id) die(sql_error());

include_once "members/members_array.php";
include_once "members/functions.php";
include_once "members/db_functions.php";
include_once "members/user_functions.php";
include_once "members/users_forms.php";

if (isset($_GET['sel'])) {
	switch ($_GET['sel']) {
		case "search":
		  switch ($_POST['search_type']) {
		  case "search_laws":
		    $include_file = "laws";
			break;
		  case "search_mp":
		    $include_file = "mp_list";
			break;
		  default:
		    $include_file = "list";
			break;
		  }
		  break;
		case "sessions":
			$include_file = "list";
			break;
		case "mp":
			$include_file = "mp_list";
			break;
		case "laws":
			$include_file = "laws";
			break;
		case "topics":
			$include_file = "topics";
			break;
		case "js_list":
			$include_file = "js_list";
			break;
		default:
		  $include_file = "home";
	}
} else {
	//$include_file = "home";
}

include "specFiles/".$include_file.".php";
?>