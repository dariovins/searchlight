<?php

// to normalize2 strings from db
function normalize_str($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    
    return strtr($string, $table);
}

function roman2arabic($rom,$letters=array()){
    if(empty($letters)){
        $letters=array('M'=>1000,
                       'D'=>500,
                       'C'=>100,
                       'L'=>50,
                       'X'=>10,
                       'V'=>5,
                       'I'=>1);
    }else{
        arsort($letters);
    }
    $arab=0;
    foreach($letters as $L=>$V){
        while(strpos($rom,$L)!==false){
            $l=$rom[0];
            $rom=substr($rom,1);
            $m=$l==$L?1:-1;
            $arab += $letters[$l]*$m;
        }
    }
    return $arab;
}

//twitter 
//gets the data from a URL  
function get_tiny_url($url)  {  
  $ch = curl_init();  
  $timeout = 5;  
  curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
  $data = curl_exec($ch);  
  curl_close($ch);  
  return $data;  
}

function isValidURI($url) {
  if (empty($url)) return false;
  return filter_var("$url", FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);
}

// to normalize strings from db
function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );
    
    return strtr($string, $table);
}


function constructURI($url, $level, $recordID) {

  $urlBase = getBaseURI();
  $includeURIBase = !isset($_SESSION['GET']['include']) ? null:"include=".$_SESSION['GET']['include']."&";

  switch ($level) {
  case 'parliament':
    $parliament = get_one_parliament($recordID);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']);
    break;
  case 'mandate':
    $mandate = get_one_mandate($recordID);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']);
    break;
  case 'session':
    $session = get_one_session($recordID);
    if ($session===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id'].(isset($_SESSION['GET']['mmp'])?"&mmp=".$_SESSION['GET']['mmp']:null));
    break;
  case 'agenda':
    $agenda = get_one_agenda_item($recordID);
    if ($agenda==false) return false;
    $session = get_one_session($agenda['session_id']);
    if ($session===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id']."&a=".$agenda['id'].(isset($_SESSION['GET']['mmp'])?"&mmp=".$_SESSION['GET']['mmp']:null));
    break;


  case 'mp_in_mandate':
    $mp_in_mandate = get_one_mp_in_mandate($recordID);
    if ($mp_in_mandate==false) return false;
    $mandate = get_one_mandate($mp_in_mandate['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id'].($recordID==$_SESSION['GET']['mmp']?null:"&mmp=".$mp_in_mandate['id']).(isset($_SESSION['GET']['s'])?"&s=".$_SESSION['GET']['s']:null).(isset($_SESSION['GET']['a'])?"&a=".$_SESSION['GET']['a']:null));
    break;



  case 'member':
    $mp = get_one_mp($recordID);
    if ($mp==false) return false;
    $url = $urlBase.base64_encode($includeURIBase."mp=".$mp['id']);
    break;
  case 'member_parliament':
    $parliament = get_one_parliament($recordID);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."mp=".$_SESSION['GET']['mp'].($recordID==$_SESSION['GET']['p']?null:"&p=".$parliament['id']));
    break;
  case 'member_mandate':
    $mandate = get_one_mandate($recordID);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    $url = $urlBase.base64_encode($includeURIBase."mp=".$_SESSION['GET']['mp']."&p=".$parliament['id'].($recordID==$_SESSION['GET']['m']?null:"&m=".$mandate['id']));
    break;
  case 'member_session':
    $session = get_one_session($recordID);
    if ($mandate_session===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id'].(isset($_SESSION['GET']['mp'])?"&mp=".$_SESSION['GET']['mp']:null));
    break;
  case 'member_agenda':
    $agenda = get_one_agenda_item($recordID);
    if ($agenda==false) return false;
    $session = get_one_session($agenda['session_id']);
    if ($sessions===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id']."&a=".$agenda['id'].(isset($_SESSION['GET']['mp'])?"&mp=".$_SESSION['GET']['mp']:null));
    break;

  case 'gazette_registry':
    $url = $urlBase.base64_encode($includeURIBase."gr=".$recordID);
    break;
  case 'gazette':
    $gazette_registry_id=get_info_gazette($recordID,"gazette_registry_id");
    $url = $urlBase.base64_encode($includeURIBase."gr=".$gazette_registry_id."&g=".$recordID);
    break;
  case 'gazette_publication':
    $gazette_id=get_info_gazette_publication($recordID,"gazette_id");
    $gazette_registry_id=get_info_gazette($gazette_id,"gazette_registry_id");
    $url = $urlBase.base64_encode($includeURIBase."gr=".$gazette_registry_id."&g=".$gazette_id."&gp=".$recordID);
    break;
  case 'gazette_publication_agenda':
    $url = $urlBase.base64_encode($includeURIBase."gr=".$_SESSION['GET']['gr']."&g=".$_SESSION['GET']['g']."&gp=".$_SESSION['GET']['gp']."&gpa=".$recordID);
    break;

  case 'topic':
    $topic = get_one_topic($recordID);
    if ($topic===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."t=".$topic['id']);
    break;
  case 'topic_institution':
    $topic = get_one_topic($_SESSION['GET']['t']);
    $url = $urlBase.base64_encode($includeURIBase."t=".$topic['id']."&i=".$recordID);
    break;
  case 'topic_acquis_chapter':
    $topic = get_one_topic($_SESSION['GET']['t']);
    $url = $urlBase.base64_encode($includeURIBase."t=".$topic['id']."&a_ch=".$recordID);
    break;
  case 'topic_gazette_publication':
    $topic = get_one_topic($_SESSION['GET']['t']);
    $url = $urlBase.base64_encode($includeURIBase."t=".$topic['id']."&gp=".$recordID);
    break;
  case 'topic_gazette_publication_agenda':
    $topic = get_one_topic($_SESSION['GET']['t']);
    $url = $urlBase.base64_encode($includeURIBase."t=".$topic['id']."&gp=".$_SESSION['GET']['gp']."&gpa=".$recordID);
    break;

    /*
  case 'session':
    $session = get_one_session($recordID);
    if ($session===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id'].(isset($_SESSION['GET']['mmp'])?"&mmp=".$_SESSION['GET']['mmp']:null));
    break;
  case 'agenda':
    $agenda = get_one_agenda_item($recordID);
    if ($agenda==false) return false;
    $session = get_one_session($agenda['session_id']);
    if ($session===false) return false;
    $mandate = get_one_mandate($session['mandate_id']);
    if ($mandate===false) return false;
    $parliament = get_one_parliament($mandate['parliament_id']);
    if ($parliament===false) return false;
    $url = $urlBase.base64_encode($includeURIBase."p=".$parliament['id']."&m=".$mandate['id']."&s=".$session['id']."&a=".$agenda['id'].(isset($_SESSION['GET']['mmp'])?"&mmp=".$_SESSION['GET']['mmp']:null));
    break;
    */

  case 'news':
    //if (isValidURI($url)=== false) $url=null; break;
    if (stripos($url,'e=')!==false) {
      $event1 = getOneEvent(substr($url,2));
      $url=base64_encode('r='.$event1['regions'].'&l='.$event1['locales'].'&e='.$event1['id']);
    }

    if (stripos($url,'http://')===false) $url = $urlBase.$url;
    if (isValidURI($url)=== false) $url=null;
 
  case 'partners':
    if (isValidURI($url) === false) $url=null;
    break;
  }

  return $url;
}

// This function wraps a page title into HREF for braadcrumbs
function wrapTitle($shift=null, $titleVar=null) {

  // limit title to 50 chars
  if (is_null($titleVar))  $title = $_SESSION['title'];
  else $title=$titleVar;
  //  $title = substr($_SESSION['title'],0,30);
  //  if (strlen($_SESSION['title'])>30) $title .= ' ...';

  // MOVE THIS TO members_array
  $qry = base64_decode($_SERVER['QUERY_STRING']);
  // truncate string to 1-$shift
  if ($shift >0) {
    // first remove the back bit
    //    if (strpos($qry, "&back")!==false) $qry = substr($qry,0,strpos($qry,strrchr($qry, "&back")));
    // then keep removing by # shifts, from the start!!!
    $myArr = explode ("&", $qry);
    //    print_r($myArr);
    for ($i=0; $i<$shift; $i++) {
      //      $qry= strchr($qry, "&", true);
      if (array_key_exists($i, $myArr)) {
	if ($i>0) $val .="&";
	$val .= $myArr[$i];
      } else {
	echo "DNE = $shift";
      }
    }
    // add the back bit but only in the last crumb than link the current pg
    if (strpos($qry, "&back")!==false && $shift==max(array_keys($myArr))) $val .= "&".$myArr[$i];
  }
  return $title = (($shift==0 || !is_null($titleVar)) ? '':' > ') . '<a href="'.$_SERVER['PHP_SELF'].'?'.base64_encode($val).'">'.$title.'</a>';
}


function bgColorShift($bgColorShift) {
  if ($bgColorShift=="#ffffff") return "#e0e8f3";
  else return "#ffffff";
}

// this function replaces a value of a specified element in the URI
// example: replace index.php?i=0 with index.php?i=1
// myKey is a element whose value is to be replaced
function updateValInURI($myURI, $myKey, $newVal) {

  $myKey=($myKey)."=";

  // get the len
  $myStrLen=strlen($myURI);

  // if empty, then just return myKey & newVal
  if ($myStrLen==0) {
    $newURI = $myKey.$newVal;
  }
  // find a position of a $myKey
  $myStrPos = strpos($myURI, $myKey);

  // if no myKey, just add it to the beginning
  if ($myStrPos === false) {
    return $newURI = $myKey.$newVal.'&'.$myURI;
  }

  // start with pos+2 to skip =1&...
  // check this +3, replace with len to capture all until next &!!!
  $mySub=substr($myURI, $myStrPos+strlen($myKey)+2);
  return $myKey.$newVal.'&'.substr($myURI, $myStrPos+strlen($myKey)+2);
}

function file_delete($file) {
  $ok = true;
  if (file_exists($file)) {
			
    if (!@unlink($file)) $ok = false;
  }
		
  return $ok;
}
	
function getFileDir($elementId = "",$naziv = "") {
	
  $elementId = str_replace("file","txt",$elementId);
		
  $tmpPath = "files/";
		
  if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 1) {
    $tmpPath .= "admin/";
  } else {
    $tmpPath .= "user".$_SESSION['userid']."/";
  }
		
  if (isset($elementId) && isset($_SESSION['CUR_UPLOAD_DIR']) && isset($_SESSION['CUR_UPLOAD_DIR'][$elementId])) {
    $tmpPath .= $_SESSION['CUR_UPLOAD_DIR'][$elementId];
  } else if (isset($_SESSION['CUR_UPLOAD_DIR'][0])) {
    $tmpPath .= $_SESSION['CUR_UPLOAD_DIR'][0];
  }
		
  if (isset($naziv) && strlen($naziv) > 0) {
    $naziv = preg_replace("/[^a-zA-Z0-9\/]/", "",$naziv);
			
    $tmpPath .= $naziv."/";
  }
		
  $tmpPathFolders = explode("/",$tmpPath);
  $tmpPathCur = "";
		
  foreach ($tmpPathFolders as $tmpFolder) {
    $tmpPathCur .= $tmpFolder."/";
			
    if (!file_exists($tmpPathCur)) {
			
      @mkdir($tmpPathCur,intval(0777, 8));
				
    }
  }
		
  return $tmpPath;
}

function getFileError($errorCode) {
  switch($errorCode) {
  case '1':
    $err_text = 'Izabrana datoteka prekoračuje dopuštenu veličinu na serveru';
    break;
  case '2':
    $err_text = 'Izabrana datoteka prekoračuje dopuštenu veličinu';
    break;
  case '3':
    $err_text = 'Izabrana datoteka je samo djelimično kopirana';
    break;
  case '4':
    $err_text = 'Datoteka nije kopirana';
    break;
  case '6':
    $err_text = 'Temp direktorij nije uredu';
    break;
  case '7':
    $err_text = 'Greška prilikom snimanja datoteke';
    break;
  case '8':
    $err_text = 'Ekstenzija datoteke nije dozvoljena';
    break;
  case '999':
  default:
    $err_text = 'Nepoznata greška';
  }
  return $err_text;
}
	
function getPages($link,$total_page,$cur_page,$rows = 0) {

  $str_pages = "";
  if ($total_page > 0 || $rows > 0) {
		
    $str_pages .= "<table width='100%' border='0' cellspacing='0' cellpadding='3'><tr>";
    $str_pages .= "<td align='left' style='font-weight:bold; height:20px;'>Pages ".($cur_page+1)."/".($total_page+1);
    if ($rows > 0) $str_pages .= " : Records ".$rows;
    $str_pages .= "</td>";
								
    $str_pages .= "<td align='right' style='font-size:10px; font-weight:bold; height:20px;'>";
    if ($cur_page > 0) $str_pages .= "<a href='".$link."&page=".($cur_page-1)."' rel='ajaxlink'><b>&laquo;</b></a>";
			
    $max = 16;
    $start = ($cur_page - ($max/2));
    if ($start < 0) $start = 0;
    $end = $start + $max-1;
    if ($end > $total_page) {
      $end = $total_page;
      $start = $end - $max+1;
      if ($start < 0) $start = 0;
    }
			
    for ($i=$start;$i<=$end;$i++) {
      if ($i == $start && $start > 0) $str_pages .= "&nbsp;...";
				
      if ($i == $cur_page) {
	$str_pages .= "&nbsp;".($i+1)."&nbsp;";
      } else {
	$str_pages .= "<a href='".$link."&page=".($i)."' rel='ajaxlink'>";
	$str_pages .= "&nbsp;".($i+1)."&nbsp;";
	$str_pages .= "</a>";
      }
      if ($i<$end) $str_pages .= "|";
				
      if ($i == $end && $end < $total_page) $str_pages .= "...&nbsp;";
    }
			
    if ($cur_page < $total_page) $str_pages .= "<a href='".$link."&page=".($cur_page+1)."' rel='ajaxlink'><b>&raquo;</b></a>";
    $str_pages .= "</td>";
    $str_pages .= "</tr></table>";
			
  }
  return $str_pages;
}

//OPTIMIZACIJA SLIKA
function resizeImage($srcW,$srcH,$dstW,$dstH) {			
  if (strtolower($dstW) == "x") {
    return round(($srcW*$dstH) / $srcH);
  } else if (strtolower($dstH) == "x") {
    return round(($srcH*$dstW) / $srcW);
  }
}
function dimensionImage($slika,$optimize = "",$dstW = 0,$dstH = 0) {
		
  $size = getimagesize($slika);
  $srcW = $size[0];
  $srcH = $size[1];
		
  if ($dstW == 0 && $dstH > 0) {
    $dstW = resizeImage($srcW,$srcH,"x",$dstH);
  } else if ($dstW > 0 && $dstH == 0) {
    $dstH = resizeImage($srcW,$srcH,$dstW,"x");
  } else if ($dstW == 0 && $dstH == 0) {
    $dstW = $srcW;
    $dstH = $srcH;	
  }
		
  $srcX = 0;
  $srcY = 0;
			
  $crpW = $dstW;
  $crpH = $dstH;
		
  if (empty($optimize)) $optimize = "default";
		
  $optimize = strtolower($optimize);
		
  $param = explode("_",$optimize);
		
  if (in_array("default",$param)) {
    //dimenzija OK
  } else if (in_array("resize",$param) || in_array("crop",$param)) {
    if (in_array("w",$param) && in_array("h",$param)) {
      $tmpHeight = resizeImage($srcW,$srcH,$dstW,"x");
      if ($tmpHeight < $dstH) {
	$tmpWidth = resizeImage($srcW,$srcH,"x",$dstH);
					
	$dstW = $tmpWidth;
      } else {
	$dstH = $tmpHeight;
      }
    } else if (in_array("w",$param)) {
      $dstH = resizeImage($srcW,$srcH,$dstW,"x");
    } else if (in_array("h",$param)) {
      $dstW = resizeImage($srcW,$srcH,"x",$dstH);
    } else {
      $tmpHeight = resizeImage($srcW,$srcH,$dstW,"x");
      if ($tmpHeight > $dstH) {
	$tmpWidth = resizeImage($srcW,$srcH,"x",$dstH);
					
	$dstW = $tmpWidth;
      } else {
	$dstH = $tmpHeight;
      }
    }
	
	
    if (in_array("crop",$param) && ($dstW > $crpW || $dstH > $crpH)) {
      /**
	 l = left
	 c = center = default
	 r = right
	 t = top
	 m = middle = default
	 b = bottom
      **/
				
      if ( !(in_array("l",$param) || in_array("c",$param) || in_array("r",$param)) ) array_push($param,"c");
      if ( !(in_array("t",$param) || in_array("m",$param) || in_array("b",$param)) ) array_push($param,"m");
				
      $ratX = $srcW / $dstW;
      $ratY = $srcH / $dstH;
	
      $dstW = $crpW;
      $dstH = $crpH;
										
      if (in_array("l",$param)) {
	$srcX = 0;
      } else if (in_array("c",$param)) {
	$srcX = floor( ($srcW - ($dstW * $ratX))/2 );
      } else if (in_array("r",$param)) {
	$srcX = floor( $srcW - ($dstW * $ratX) );
      }
      $srcW = floor( $srcW - ($srcW - ($dstW * $ratX)) );
		
      if (in_array("t",$param)) {
	$srcY = 0;
      } else if (in_array("m",$param)) {
	$srcY = floor( ($srcH - ($dstH * $ratY))/2 );
      } else if (in_array("b",$param)) {
	$srcY = floor( $srcH - ($dstH * $ratY) );
      }
      $srcH = floor( $srcH - ($srcH - ($dstH * $ratY)) );
    }//if (in_array("crop",$param))
			
  }
		
  return array($srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
}
	
function resize($slika,$twidth,$theight) {
  $size = getimagesize($slika);
  $fwidth = $size[0];
  $fheight = $size[1];
			
  if (strtolower($twidth) == "x") {
    return round(($fwidth*$theight) / $fheight);
  } else if (strtolower($theight) == "x") {
    return round(($fheight*$twidth) / $fwidth);
  }
}	

function get_extenzion($direktorij,$file_name,$ext = array(".jpg",".gif",".png",".pdf",".xls",".doc",".docx", ".zip")) {
		
  if (substr($direktorij,-1) != "/") $direktorij = $direktorij."/";
		
  foreach ($ext as $value) {
     if (file_exists($direktorij.$file_name.$value)) {
       return $value;
    }
  }
		
  return "";
}

function get_directory($dir, $type = "fajl") {
  $lista_dir = array();
  $lista_fajl = array();
		
  $only = basename($dir);
  if ($only) $dir = dirname($dir)."/";
	
  if (is_dir($dir)) {
    $dp=opendir($dir);
    while (false != ($fajl=readdir($dp))) {
      if (is_file($dir.$fajl) and $fajl!="." and $fajl!=".." and strtoupper($only) == strtoupper(substr($fajl,0,strlen($only)))) {
					
	if ($type == "fajl") array_push ($lista_fajl,$dir.$fajl);
	else if ($type == "fajl_name") array_push ($lista_fajl,$fajl);
      } else if (is_dir($dir.$fajl) and $fajl!="." and $fajl!=".." and strtoupper($only) == strtoupper(substr($fajl,0,strlen($only)))) {
				
	if ($type == "dir") array_push ($lista_dir,$dir.$fajl);
	else if ($type == "dir_name") array_push ($lista_dir,$fajl);
      }
    }
  }
		
  if ($type == "fajl" || $type == "fajl_name") {
    return $lista_fajl;
  } else if ($type == "dir" || $type == "dir_name") {
    return $lista_dir;
  }
		
}
function get_pictures($dir,$name,$max = 10) {

  $lista_fajl = array();
		
  if (substr($dir,-1) != "/") $dir = $dir."/";
		
  for ($i = 1; $i <= $max; $i++) {
			
    $fajl = $dir.$name.$i.get_extenzion($dir,$name.$i);
			
    if (file_exists($fajl)) {
      array_push ($lista_fajl,$fajl);
    }
  }
		
  return $lista_fajl;
}
?>