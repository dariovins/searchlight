<?php

// replace with DB tables later
function getBaseURI() {
  global $BASE_URI;
  return $BASE_URI;
}

function getStatus($status) {
  global $STATUS;
  if(!array_key_exists($status, getStatuses())) return false;
  
  return $STATUS[$status];
}
function getStatuses() {
  global $STATUS;
  return $STATUS;
}


function urldecode_data($value) {
	return urldecode($value);
}

/** 
 * COUNTRY/LANGUAGE MODULE
 */
function get_countries($id=null) {
  $query = "SELECT * FROM meta_country WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_country($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_countries($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_country($id,$db_column) {
  $record = get_one_country($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_language_in_country($id) {
  $record = get_one_country($id);
  if ($record===false || !array_key_exists($language_id,$record)) return false;
  return get_info_language($language_id,"title");
}

function get_languages($id=null) {
  $query = "SELECT * FROM meta_language WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_language($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_languages($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_language($id,$db_column) {
  $record = get_one_language($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_one_language_by_name($name) {
  $reg = get_languages();
  foreach ($reg as $key=>$val) {
    if ($name==$val["short_title"]) return $val;
  }
  return false;
}


/****************************************************/

/** 
* TRANSLATION - LANG TABLE MODULE
*/
function get_lang_table($table,$id,$language_id) {
  // if default language, return false
  if ($language_id==1) return false;
  // check for table
  $query = "show tables like '".$table."'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;

  $query = "SELECT * FROM lang_".$table." WHERE 1=1";
  if(isset($id)) $query .= " AND ".$table."_id=".$id;
  if(isset($language_id)) $query .= " AND language_id=".$language_id;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  $data = mysql_fetch_assoc($result);
  return $data;
}

// GUI LABEL MODULE
function get_gui_label($field_id,$default = '') {
  // check for table
	if (isset($_SESSION['userlang']) && $_SESSION['userlang'] > 0) {
		$language_id = $_SESSION['userlang'];
	} else {
		$language_id = 1;
	}
	
  $query = "SELECT title FROM lang_gui_label WHERE field_id = '".$field_id."'";
  $result = mysql_query($query) or die(mysql_error());
  if ($data = mysql_fetch_row($result)) {
		return urldecode($data[0]);
	} else {
		return $default;
	}
}

// GUI TABLES
function get_gui_table($table,$filter_field=null,$filter_value=null) {

  // check for table
  $query = "show tables like '".$table."'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;

  $query = "SELECT * FROM gui_".$table." WHERE 1=1";
  if(isset($filter_field) && isset($filter_field)) $query .= " AND ".$filter_field."=".$filter_value;
  $query .= " ORDER BY id";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_gui_table($table,$filter_field=null,$filter_value=null) {
  $rec=get_gui_table($table,$filter_field,$filter_value);
  if ($rec===false) return false;
    return $rec[0];
}
function get_info_gui_table($table,$db_column,$id) {

  $gui_table_record=get_one_gui_table($table,"id",$id);
  if ($gui_table_record===false) return false;
  
  if (!array_key_exists($db_column,$gui_table_record))return false;

  return $gui_table_record[$db_column];
}


/****************************************************/

/** 
 * META DATA
 */

function get_level_types($id=null) {
  $query = "SELECT * FROM meta_level_type WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_level_type($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_level_types($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_level_type($id,$db_column) {
  $record = get_one_level_type($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
/****************************************************/
function get_lgus($id=null) {
  $query = "SELECT * FROM meta_lgu WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_lgu($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_lgus($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_lgu($id,$db_column) {
  $record = get_one_lgu($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
/****************************************************/
function get_document_types($id=null) {
  $query = "SELECT * FROM meta_document_type WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_document_type($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_document_types($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_document_type($id,$db_column) {
  $record = get_one_document_type($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_document_type_id_by_name($id=null) {
  $query = 'SELECT id FROM meta_document_type WHERE title="$title"';
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
/****************************************************/
function get_political_parties($id=null) {
  $query = "SELECT * FROM meta_political_party WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;

    $i++;
  }
  return $record;
}
function get_one_political_party($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_political_partys($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_political_party($id,$db_column) {
  $record = get_one_political_party($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}





/**
 * EU Policy Areas MODULE
 */
function get_theme_policy_area_group($id=null) {
  $query = "SELECT * FROM theme_policy_area_group WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_theme_policy_area($theme_policy_area_group_id=null,$id=null) {
  $query = "SELECT * FROM theme_policy_area WHERE 1=1";
  if(isset($theme_policy_area_group_id)) $query .= " AND theme_policy_area_group_id=".$theme_policy_area_group_id;
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_theme($theme_policy_area_id=null,$id=null) {
  $query = "SELECT * FROM theme_ WHERE 1=1";
  if(isset($theme_policy_area_id)) $query .= " AND theme_policy_area_id=".$theme_policy_area_id;
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_theme_topic($theme_policy_area_id=null,$id=null) {
  $query = "SELECT * FROM theme_ WHERE 1=1";
  if(isset($theme_policy_area_id)) $query .= " AND theme_policy_area_id=".$theme_policy_area_id;
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}




/**
 * ACQUIS MODULE
 */
function get_acquis_chapters($id=null) {
  $query = "SELECT * FROM meta_acquis_chapter WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_acquis_chapter($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_acquis_chapters(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_acquis_chapter($id,$db_column) {
  $record = get_one_acquis_chapter($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

function get_acquis($acquis_chapter_id=null,$id=null) {
  $query = "SELECT * FROM acquis WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  if(isset($acquis_chapter_id)) $query .= " AND acquis_chapter_id=".$acquis_chapter_id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_acquis($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_acquis_chapters(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_acquis($id,$db_column) {
  $record = get_one_acquis_chapter($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}







/**
 * PARLIAMENT MODULE
 */
function get_parliaments($country_id=null,$level_type=null,$id=null) {
  $query = "SELECT * FROM parliament WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  if(isset($country_id)) $query .= " AND country_id=".$country_id;
  if(isset($level_type_id)) $query .= " AND level_type_id=".$level_type_id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_parliament($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_parliaments(null,null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_parliament($id,$db_column) {
  $record = get_one_parliament($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}



/**
 * MP MODULE
 */
function get_mps($id=null) {
  $query = "SELECT * FROM mp WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    //  thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_mp($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_mps($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_mp($id,$db_column) {
  $record = get_one_mp($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
// fetch member data by screen name from session minutes
function  get_mp_by_screen_name($screen_name) {
  $query = "SELECT id FROM mp WHERE screen_name='$screen_name'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $data = mysql_fetch_assoc($result);
  if ($data===false) return false;
  return $data['id'];
}
function get_mp_alias($mp_id) {
  if (get_one_mp($mp_id)===false) return false;
  $query = "SELECT alias from mp_alias WHERE mp_id=$mp_id";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data=mysql_fetch_assoc($result))
    {
      $record[$i]=urldecode($data['alias']);
      $i++;
    }
  return $record;
}

/**
 * PEOPLE MODULE -- these are non=MPs showing up in mandates and sessions records
 */
function get_people($id=null) {
  $query = "SELECT * FROM people WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    //  thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_people($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_people($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_people($id,$db_column) {
  $record = get_one_people($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
// fetch member data by screen name from session minutes
function  get_people_by_screen_name($screen_name) {
  $query = "SELECT id FROM people WHERE screen_name='$screen_name'";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $data = mysql_fetch_assoc($result);
  if ($data===false) return false;
  return $data['id'];
}
function get_people_alias($people_id) {
  if (get_one_people($people_id)===false) return false;
  $query = "SELECT alias from people_alias WHERE people_id=$people_id";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data=mysql_fetch_assoc($result))
    {
      $record[$i]=urldecode($data['alias']);
      $i++;
    }
  return $record;
}





/**
 * MANDATE MODULE
 */
function get_mandates($parliament_id=null,$mandate_id=null) {
  $query = "SELECT * FROM mandate WHERE 1=1";
  if(isset($parliament_id)) $query .= " AND parliament_id=".$parliament_id;
  if(isset($mandate_id)) $query .= " AND id=".$mandate_id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY parliament_id, id";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_mandate($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_mandates(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_mandate($id,$db_column) {
  $record = get_one_mandate($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}


/**
 * MP in Mandate
 */
function get_mps_in_mandates($mandate_id=null, $mp_id=null, $id=null,$date_start=null,$date_end=null) {
  $query = "SELECT * FROM mp_in_mandate WHERE 1=1";
  if(isset($mandate_id)) $query .= " AND mandate_id=".$mandate_id;
  if(isset($mp_id)) $query .= " AND mp_id=".$mp_id;
  if(!empty($id)) $query .= " AND id=".$id;
  if(!empty($date_start)) $query .= " AND date_start<='".$date_start."'";
  if(!empty($date_end)) $query .= " AND date_end>='".$date_end."'";
  // echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_mp_in_mandate($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_mps_in_mandates(null,null,$id);
  if ($reg===false || !is_array($reg) || sizeof($reg)==0) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_mp_in_mandate($id,$db_column) {
  $record = get_one_mp_in_mandate($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

// get all mps for 1 mandate
function get_mps_in_mandate($mandate_id,$date_start=null,$date_end=null) {
  $reg = get_mps_in_mandates($mandate_id,null,null,$date_start,$date_end);
  if ($reg===false || !is_array($reg) || sizeof($reg)==0) return $reg;
  return $reg;
}
// get all mandates for 1 mp
function get_mps_in_mandate_screen_name ($mandate_id) {
  $reg = get_mps_in_mandates($mandate_id);
  if ($reg===false || !is_array($reg) || sizeof($reg)==0) return $reg;
  foreach ($reg as $key=>$record) {
    $data[$record['id']]=get_info_mp($record['mp_id'],'screen_name');
  }
  return $data;
}
// get all mandates for 1 mp
function get_mp_in_mandate_record_for_mp($mp_id) 
{
  $reg = get_mps_in_mandates(null,$mp_id);
  if ($reg===false || !is_array($reg) || sizeof($reg)==0) return $reg;
  return $reg;
}


/**
 * PEOPLE in Mandate
 */
function get_people_in_mandates($mandate_id=null, $people_id=null, $id=null,$date_start=null,$date_end=null) {
  $query = "SELECT * FROM people_in_mandate WHERE 1=1";
  if(isset($mandate_id)) $query .= " AND mandate_id=".$mandate_id;
  if(isset($people_id)) $query .= " AND people_id=".$people_id;
  if(!empty($id)) $query .= " AND id=".$id;
  if(!empty($date_start)) $query .= " AND date_start<='".$date_start."'";
  if(!empty($date_end)) $query .= " AND date_end>='".$date_end."'";
  // echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_people_in_mandate($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_people_in_mandates(null,null,$id);
  if ($reg===false || !is_array($reg) || sizeof($reg)==0) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_people_in_mandate($id,$db_column) {
  $record = get_one_people_in_mandate($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

// PEOPLE TYPES - gov't embassy international ngo guest unknown other
function get_people_types($id=null) {
  $query = "SELECT * FROM people_type WHERE 1=1";
  if (isset($id)) $query .= " AND id=$id";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_people_type($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_people_types($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_people_type($id,$db_column) {
  $record = get_one_people_type($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}



/**
 * SESSION MODULE
 */
function get_sessions($mandate_id=null, $id=null) {
  $query = "SELECT * FROM session WHERE 1=1";
  if(isset($mandate_id)) $query .= " AND mandate_id=".$mandate_id;
  if(isset($id)) $query .= " AND id=".$id;
  $query .= " ORDER BY mandate_id, session_date, session_no";
  //echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_session($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_sessions(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_session($id,$db_column) {
  $record = get_one_session($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
// get ALL session data *full record - by date
function get_sessions_by_date($date_start,$date_end,$mandate_id=null) {
  $query = "SELECT * FROM session WHERE session_date>='".$date_start."' AND session_date<='".$date_end."'";
  if(isset($mandate_id)) $query .= " AND mandate_id=".$mandate_id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
// return full mp record
function get_session_chairman($session_id) {
  $chair_id=get_info_session($session_id,"chair_mp_in_mandate_id");
  if ($chair_id===false) return false;
  $mp=get_one_mp($chair_id);
  if ($mp===false) return false;
  // addmp_in_mandate_id to the record
  $mp['mp_in_mandate_id']=$chair_id;
  return $mp;
}

function get_session_documents($session_id=null, $id=null) {
  $query = "SELECT * FROM session_document WHERE 1=1";
  if(isset($session_id)) $query .= " AND session_id=".$session_id;
  if(isset($id)) $query .= " AND id=".$id;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_session_document($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_session_documents(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_session_document($id,$db_column) {
  $record = get_one_session_document($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_session_documents_by_type($document_type_id,$session_id=null) {
  $query = "SELECT sd.*, mdt.title FROM session_document as sd, meta_document_type as mdt WHERE 1=1 AND sd.document_type_id=mdt.id AND mdt.id=$document_type_id";
  if(isset($session_id)) $query .= " AND sd.session_id=".$session_id;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}

/**
 * MP in session
 */
// get all sessions for MP in a mandate
function get_mps_in_session($session_id, $mp_in_mandate_id=null) {
  $query = "SELECT * FROM mp_in_session WHERE 1=1";
  if(isset($session_id)) $query .= " AND session_id=".$session_id;
  if(isset($mp_in_mandate_id)) $query .= " AND mp_in_mandate_id=".$mp_in_mandate_id;
  //echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  while ($data = mysql_fetch_assoc($result))
    {
      $record[] = $data['mp_in_mandate_id'];
     }
  return $record;
}
function get_session_id_for_mp_in_mandate($mp_in_mandate_id) {
  $query = "SELECT * FROM mp_in_session WHERE 1=1";
  $query .= " AND mp_in_mandate_id=".$mp_in_mandate_id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  while ($data = mysql_fetch_assoc($result))
    {
      $record[] = $data['session_id'];
     }
  return $record;
}



/**
 * AGENDA MODULE
 */
function get_agenda($session_id=null,$id=null) {
  $query = "SELECT * FROM agenda WHERE 1=1";
  if(!is_null($session_id)) $query .= " AND session_id=".$session_id;
  if(!is_null($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_agenda($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_agenda(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_agenda($id,$db_column) {
  $record = get_one_agenda($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

function get_agenda_documents($agenda_id=null,$id=null) {
  $query = "SELECT * FROM agenda_document WHERE 1=1";
  if(isset($agenda_id)) $query .= " AND agenda_id=".$agenda_id;
  if(isset($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_agenda_document($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_agenda_documents(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_agenda_document($id,$db_column) {
  $record = get_one_agenda_document($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
// get related agenda items to the given above agenda_id -- from left to right
function get_rel_agenda($agenda_id_above) {
  $query = "SELECT agenda_id_below as agenda_id FROM agenda_rel WHERE 1=1";
  $query .= " AND agenda_id_above=".$agenda_id_above;
  $query .= " ORDER BY agenda_id_below DESC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    $record[$i] = $data['agenda_id'];
    $i++;
  }
  return $record;
}
// get list of agenda_id with gazette_publication_id
function get_agenda_id_with_gazette_publication_id($gazette_publication_id) {
  $query = "SELECT a.id FROM agenda as a, session as s WHERE session_id=s.id";
  $query .= " AND gazette_publication_id=".$gazette_publication_id;
  $query .= " ORDER BY s.session_date DESC, a.agenda_no DESC";
  //    echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    $record[$i] = $data;
    $i++;
  }
  return $record;
}
function get_agenda_and_session_by_agenda_id($agenda_id) {
  $query = "SELECT a.id as agenda_id, a.title as agenda_title, a.*, s.* FROM agenda as a, session as s WHERE a.session_id=s.id";
  $query .= " AND a.id IN (".implode(",",$agenda_id).")";
  $query .= " ORDER BY s.session_date DESC, a.agenda_no DESC";
  //echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    foreach ($data as $key=>$val)
      {
	$record[$i][$key]=urldecode($val);
      }
    //    $record[$i] = $data;
    $i++;
  }
  return $record;
}

// TO DO - build agendatype+keywords functions


/**
 * DEBATE MODULE
 */
function get_debates($session_id=null,$agenda_id=null,$mp_in_mandate_id=null, $id=null) {
  if ((empty($session_id) || is_null($session_id)) && (empty($agenda_id) || is_null($agenda_id))) return false;
  
  $query = "SELECT * FROM agenda_debate WHERE 1=1";
  if(!is_null($session_id)) $query .= " AND session_id=".$session_id;
  if(!is_null($agenda_id)) $query .= " AND agenda_id=".$agenda_id;
  if(!is_null($mp_in_mandate_id)) $query .= " AND mp_in_mandate_id=".$mp_in_mandate_id;
  if(!is_null($id)) $query .= " AND id=".$id;
  $query .= " ORDER BY id ASC";
  // echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_debate($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_debates(null,null,null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_debate($id,$db_column) {
  $record = get_one_debate($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_debates_for_session($session_id) {
  $reg = get_debates($session_id);
  if ($reg===false) return false;
  return $reg;
}
function get_debates_for_agenda($agenda_id) {
  $reg = get_debates(null,$agenda_id);
  if ($reg===false) return false;
  return $reg;
}
function get_debates_for_mp_in_mandate($mp_in_mandate) {
  $reg = get_debates(null,null,$mp_in_mandate);
  if ($reg===false) return false;
  return $reg;
}
function get_debates_for_mp($mp_id) {
  $mpm=get_mp_in_mandate_record_for_mp($mp_id);
  foreach ($mpm as $key=>$record)
      {
	$reg = get_debates(null,null,$record['id']);
	if ($reg===false) return false;
	foreach ($reg as $key=>$record2)
	  {
	    $data[]=$record2;
	  }
      }
  return $data;
}







/**
 * VOTING MODULE
 */
function get_votes($agenda_id=null,$mp_in_mandate_id=null) {
  if (is_null($agenda_id) && is_null($mp_in_mandate_id)) return false; // prevent full query
  $query = "SELECT * FROM agenda_vote WHERE 1=1";
  if(!is_null($agenda_id)) $query .= " AND agenda_id=".$agenda_id;
  if(!is_null($mp_in_mandate_id)) $query .= " AND mp_in_mandate_id=".$mp_in_mandate_id;
  //echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $record=mysql_fetch_assoc($result);
  $record['vote_bckg_hex_color']='#'.get_info_vote_type($record['vote_type_id'],'type_bckg_hex_color');
  $record['vote_font_hex_color']='#'.get_info_vote_type($record['vote_type_id'],'type_font_hex_color');
  return $record;
}
function get_one_vote($agenda_id,$mp_in_mandate_id) {
  if (is_null($agenda_id) || empty($agenda_id)) return false;
  $reg = get_votes($agenda_id,$mp_in_mandate_id);
  return $reg;
}

function get_vote_types($id=null) {
  $query = "SELECT * FROM meta_vote_type WHERE 1=1";
  if (isset($id)) $query .= " AND id=$id";
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_vote_type($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_vote_types($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_vote_type($id,$db_column) {
  $record = get_one_vote_type($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}
function get_votes_for_agenda($agenda_id) {
  $reg = get_votes($agenda_id);
  if ($reg===false) return false;
  return $reg;
}
function get_votes_for_mp_in_mandate($mp_in_mandate) {
  $reg = get_votes(null,$mp_in_mandate);
  if ($reg===false) return false;
  return $reg;
}







/**
 * GAZETTE MODULE
 */
function get_gazette_registry($level_type_id=null,$id=null) {
  $query = "SELECT * FROM gazette_registry WHERE 1=1";
  if(isset($level_type_id)) $query .= " AND level_type_id=".$level_type_id;
  if(isset($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_gazette_registry($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_gazette_registry(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_gazette_registry($id,$db_column) {
  $record = get_one_gazette_registry($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

function get_gazettes($gazette_registry_id=null, $id=null) {
  $query = "SELECT * FROM gazette WHERE 1=1";
  if(isset($gazette_registry_id)) $query .= " AND gazette_registry_id=".$gazette_registry_id;
  if(isset($id)) $query .= " AND id=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_gazette($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_gazettes(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_gazette($id,$db_column) {
  $record = get_one_gazette($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}

function get_gazette_publications($gazette_publication_group_id=null,$gazette_id=null,$id=null) {
  $query = "SELECT * FROM gazette_publication WHERE 1=1";
  if(isset($gazette_publication_group_id)) $query .= " AND gazette_publication_group_id=".$gazette_publication_group_id;
  if(isset($gazette_id)) $query .= " AND gazette_id=".$gazette_id;
  if(isset($id)) $query .= " AND id=".$id;
  // echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_gazette_publication($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_gazette_publications(null,null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_gazette_publication($id,$db_column) {
  $record = get_one_gazette_publication($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}


// returns a list of agenda-Ids of related pubs to the Above_id 
function get_rel_gazette_publication($direction,$id) {

  if (!in_array($direction,array('past','future'))) return false;

  $query = "SELECT gazette_publication_id_".($direction=="past"?"below":"above")." as id FROM gazette_publication_rel WHERE 1=1";
  $query .= " AND gazette_publication_id_".($direction=="past"?"above":"below")."=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $data = mysql_fetch_assoc($result);
  return $data['id'];
}
// returns a list of agenda-Ids of related pubs to the Above_id 
function get_rel_gazette_publication_list($direction,$gazette_publication_id) {

  if (!in_array($direction,array('past','future'))) return false;

  $id=get_rel_gazette_publication($direction,$gazette_publication_id);
  if ($id===false) return false;
  else
    {
      $i=0;
      $record[$i]=$id;
      while ($id!==false)
	{
	  $id=get_rel_gazette_publication($direction,$id);
	  if ($id===false) break;
	  $record[$i]=$id;
	  $i++;
	}
    } //end while
  return $record;
}


function get_gazette_publication_groups($gazette_publication_group_type_id=null,$id=null) {
  $query = "SELECT * FROM gazette_publication_group WHERE 1=1";
  if(isset($gazette_publication_group_type_id)) $query .= " AND gazette_publication_group_type_id=".$gazette_publication_group_type_id;
  if(isset($id)) $query .= " AND id=".$id;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_gazette_publication_group($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_gazette_publication_groups($id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_gazette_publication_group($id,$db_column) {
  $record = get_one_gazette_publication_group($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}


// returns a list of related laws - horisonntal link
function get_rel_gazette_publication_group($direction,$id) {

  if (!in_array($direction,array('left','right'))) return false;

  $query = "SELECT gazette_publication_group_id_".($direction=="left"?"left":"right")." as id FROM gazette_publication_group_rel WHERE 1=1";
  $query .= " AND gazette_publication_group_id_".($direction=="left"?"right":"left")."=".$id;
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $data = mysql_fetch_assoc($result);
  return $data['id'];
}
// returns a list of agenda-Ids of related pubs to the Above_id 
function get_rel_gazette_publication_group_list($direction,$gazette_publication_group_id) {

  if (!in_array($direction,array('left','right'))) return false;

  $id=get_rel_gazette_publication_group($direction,$gazette_publication_group_id);
  if ($id===false) return false;
  else
    {
      $i=0;
      $record[$i]=$id;
      while ($id!==false)
	{
	  $id=get_rel_gazette_publication_group($direction,$id);
	  if ($id===false) break;
	  $record[$i]=$id;
	  $i++;
	}
    } //end while
  return $record;
}



function get_gazette_publication_groups_by_name($name) {
  $query = "SELECT * FROM gazette_publication_group WHERE title like '%".$name."%'";
  if(isset($id)) $query .= " AND id=".$id;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}






  /**
   * INSTITUTIONS
   */
function get_institutions($country_id=null,$id=null) {
  $query = "SELECT * FROM institution WHERE 1=1";
  if(isset($id)) $query .= " AND id=".$id;
  if(isset($country_id)) $query .= " AND country_id=".$country_id;
  $query .= " AND Deleted='N'";
  $query .= " ORDER BY poz ASC";
  //  echo $query;
  $result = mysql_query($query) or die(mysql_error());
  if (mysql_num_rows($result) == 0) return false;
  $i=0;
  while ($data = mysql_fetch_assoc($result)) {
    // loop thru array to decode
    foreach ($data as $key=>$val) {
      $data1[$key] = urldecode($val);
    }
    $record[$i] = $data1;
    $i++;
  }
  return $record;
}
function get_one_institution($id) {
  if (is_null($id) || empty($id)) return false;
  $reg = get_institutions(null,$id);
  if ($reg===false) return $reg;
  foreach ($reg as $key=>$val) {
    return $val;
  }
}
function get_info_institution($id,$db_column) {
  $record = get_one_institution($id);
  if ($record===false || !array_key_exists($db_column,$record)) return false;
  return $record[$db_column];
}








/**
 * utils
 */
function compare_arrays_with_session_date($a, $b)
{
  $t1 = strtotime($a['session_date']);
  $t2 = strtotime($b['session_date']);
  return $t2 - $t1;
}    

/**
 * TO BE COMPLETED
 */
function get_filters_minutes() {
  return array(
	       'DNEVNI RED',
	       'PARLAMENTARNE SKUPSTINE',
	       );
}


?>