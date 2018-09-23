<?php
// start session
if (!isset($_SESSION)) session_start();


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


/**
 * SWITCHHBOARD
 */
if (isset($_GET['sel'])) {
	switch ($_GET['sel']) {
		case "sessions":
			$include_file = "list";
			$include_css_file = "list";
			break;
		case "mp":
			$include_file = "mp_list";
			$include_css_file = "mp_list";
			break;
		case "topics":
   		$include_file = "topics";
   		$include_css_file = "list";
			break;
		case "laws":
			$include_file = "laws";
			$include_css_file = "list";
			break;
		case "search":
		  if (empty($_POST['search'])) {
		    $include_file = $include_css_file = 'list'; $_GET['sel'] = 'sessions';
		    if ($_POST['search_type']=='search_mp') $include_file = $include_css_file = 'mp_'.$include_file; $_GET['sel'] = 'mp';
		    if ($_POST['search_type']=='search_laws') $include_file = $_GET['sel'] = 'laws';
		    echo $include_file . " - " . $include_css_file;
		  }
		  else
		    {
		      $include_file = $_POST['search_type'];
		      $include_css_file = $_POST['search_type'];
		    }
			break;
		default:
		  	    $include_file = "home";
		  	$include_css_file = "home";
	}
} else {
	$include_file = "home";
	$include_css_file = "home";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Think Tank Populari | Searchlight</title>
<link href="css/global.css" rel="stylesheet" type="text/css">
<?php
  echo '<link href="css/'.$include_css_file.'.css" rel="stylesheet" type="text/css">';
?>
<link href="jquery/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery/jquery-ui.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery/common.js" charset="utf-8"></script>

<script src="jquery/highcharts/js/highcharts.js"></script>
</head>
<script language="javascript">
	$(document).ready(function(){ 




		
		var searchBox_timer = null;
		
		function searchBox(show) {
			if (searchBox_timer) clearTimeout(searchBox_timer);
			
			if (show) {
				$('#search_type').slideDown();
			} else {
				$('#search_type').slideUp();
			}
		}
		
		$('#search').focus(function() {
			searchBox(true);
		}).blur(function() {
			searchBox_timer = setTimeout(function() {
				searchBox(false);
			}, 1000);
		}).click(function() {
			searchBox(true);
		});
		
		$('#search_type').hover(
			function () {
				searchBox(true);
			},
			function () {
				searchBox_timer = setTimeout(function() {
					searchBox(false);
				}, 1000);
			}
		);
				



  	var footerTop = $(".main-footer-wrap").offset().top;
	  var footerBottom = footerTop + $(".main-footer-wrap").height();
		
		if ($(window).height() > footerBottom) {
			$(".main-footer-wrap").css({"position":"fixed","z-index":100,"left":0,"bottom":0});
			$(".page-wrapper").css("padding-bottom","150px");
		}
		
		$("#signin").click(function () {
			
			ShowHide("login-window");
			
			$.ajax({
				type: "GET",
				url: "auth/authentication_list.php",
				scriptCharset: 'utf-8',
				success: function(html){
					$("#changeuser_content").html(html);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#changeuser_content").html("<font style='color: #CC0000'>Greška: </font> Stranica nije učitana ...");
				}
			});
			
		});
		
		$("#login-form").submit(function(event) {
			
			event.preventDefault(); 
			
			$.ajax({
				type: "POST",
				dataType: "json",
				url: $(this).attr("action"),
				data: $(this).serialize(),
				scriptCharset: 'utf-8',
				success: function(data){
					
					if (data.errText.length > 0) {
						$("#login-form .error_box").html(data.errText).show();
					} else {
						$("#login-window").hide();
						
						$("#signin").html(data.label);
					}
					
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#login-form .error_box").html(textStatus).show();
				}
			});
			
		});
		
		$("#searchform").submit(function(event) {
			
			event.preventDefault(); 
			
			var searchType = $("input[name='search_type']:checked").val();
			
			if (searchType == "search_list") {
				urlLink = "index.php?sel=sessions";
			} else if (searchType == "search_laws") {
				urlLink = "index.php?sel=laws";
			} else if (searchType == "search_mp") {
				urlLink = "index.php?sel=mp";
			}
			
			urlLink += "&search=" + $("#search").val();
			
			location.href = urlLink;
			
		});
		
	});
</script>
<!-- <body class="<?php echo (isset($_SESSION['userid']) && $include_file == "home") ? "orange":"white"; ?>"> -->
<body class="orange">



  <?php if (isset($_GET['sel']) && (in_array($_GET['sel'], array('sessions','mp','laws','search')))) { ?>

<div class="header-wrapper">
  <header class="main-header">
    <nav class="header-nav"><span class="home-link"><a href="index.php"><img src="css/images/sl_logo.png" style="border:0;height:25px;" \></a></span>
      <?php if ($_GET['sel'] == 'sessions' || $_GET['sel'] == 'mp') { ?>

      <div class="select-box" style="position:absolute;top:7px;left:223px;color:#fff;">
        <ul class="custom-select">
	  <?php
	     $parliaments = get_parliaments();
	     
	     if (!isset($_GET['p'])) {
	       $_GET['p'] = $parliaments[0]['id'];
	       $cur_title = $parliaments[0]['title'];
	     } else {
	       foreach ($parliaments as $value) {
		 if ($value['id'] == $_GET['p']) {
		   $cur_title = $value['title'];
		   break;
		 }
	       }
	     }
	     ?>
          <li><a style="color:#fff; font-size:12px; line-height:150%;" href="index.php?sel=<?php echo $_GET['sel']; ?>&p=<?php echo $_GET['p']; ?>"><?php echo $cur_title; ?></a>
            <ul>
              <?php foreach ($parliaments as $value) { ?>
	      <li><a href="index.php?sel=<?php echo $_GET['sel']; ?>&p=<?php echo $value['id']; ?>"><?php echo $value['title']; ?></a></li>
	      <?php } ?>
            </ul>
          </li>
        </ul>
      </div>
      
      <div class="select-box" style="position:absolute;top:7px;left:458px;color:#fff;">
        <ul class="custom-select">
          <?php
	       $mandates = get_mandates($_GET['p']);
	     
	     if (!isset($_GET['m'])) {
	       $_GET['m'] = $mandates[count($mandates)-1]['id'];
	       $cur_title = $mandates[count($mandates)-1]['title'];
	     } else {
	       foreach ($mandates as $value) {
		 if ($value['id'] == $_GET['m']) {
		   $cur_title = $value['title'];
		   break;
		 }
	       }
	     }
	     
	     ?>
          <li><a style="color:#fff; font-size:12px; line-height:150%;" href="index.php?sel=<?php echo $_GET['sel']; ?>&p=<?php echo $_GET['p']; ?>&m=<?php echo $_GET['m']; ?>">Mandate: <?php echo $cur_title; ?></a>
            <ul>
              <?php foreach ($mandates as $value) { ?>
              <li><a href="index.php?sel=<?php echo $_GET['sel']; ?>&p=<?php echo $_GET['p']; ?>&m=<?php echo $value['id']; ?>">Mandate: <?php echo $value['title']; ?></a></li>
	      <?php } ?>
            </ul>
          </li>
        </ul>
      </div>

      <?php } // end if sel = sessions or mp?>


      <?php if ($_GET['sel'] == 'laws') { ?>
<!--
      <div class="select-box" style="position:absolute;top:7px;left:223px;color:#fff;">
        <ul class="custom-select">
          <!-- when selected add:  class="focus-f" to li below  --
          <li><a style="color:#fff; font-size:12px; line-height:150%;" href="#">Gazette Year: 2012</a>
            <ul>
              <li><a href="#">2011</a></li>
	      <li><a href="#">2010</a></li>
	      <li><a href="#">2009</a></li>
            </ul>
          </li>
        </ul>
      </div>
-->
      <?php } ?>


      <?php if ($_GET['sel'] == 'search') { ?>
      <div class="ui-corner-all"  style="position:absolute;top:6px;height:27px;left:223px;padding:5px;background:#efae3e;">
	<h4>Prikazujem rezultate za: "<?php echo $_POST['search']; ?>"</h4>
      </div>
      <?php } ?>


      <div style="position:absolute; left:695px;">
	<form id="searchform">
	  <h4 style="padding-top: 6px;padding-bottom: 10px;float:left;">
	    <input id="search" name="search" type="text" class="ui-corner-all"  style="width:250px;height:27px;" autocomplete="off" />
	    <div id="search_type" class="ui-corner-all" style="background:#c60;">
	      <label><input name="search_type" type="radio" value="search_list" checked> Sjednice, dnevni red, debate</label>
	      <label><input name="search_type" type="radio" value="search_laws" /> Zakoni</label>
	      <label><input name="search_type" type="radio" value="search_mp" /> Poslanici i delegati</label>
	    </div>
	  </h4>
	  <input type="submit" style="text-rendering: optimizeLegibility;background:transparent;border:0;cursor:pointer;float:left;padding:13px;color:#efae3e;font-weight:normal;font-size:14px" value="Pretraži Searchlight" />
	</form>
      </div> 

    </nav>
  </header>
</div>


  <?php } ?>




<div class="page-wrapper">





<?php
  /**
   * dirty hack to ask for login
   */
  
  //    if (!isset($_SESSION['userid'])) {
  //  login_form();
  //    } else {
?>



  <?php if (isset($_GET['sel']) && (in_array($_GET['sel'], array('sessions','mp','laws')))) { ?>
<script language="javascript">
	$(document).ready(function(){
		
		$(".select-box > .custom-select > li").hover(
			function () {
				$(this).addClass("sfhover");
			},
			function () {
				$(this).removeClass("sfhover");
			}
		);
		
	});
</script>



   <?php 
    }
  //print_r($_POST); 

if (isset($_GET['sel'])) { ?>
<div class="slides-control" style="position:absolute;top:60px;left:20px;width:150px;">
  <span class="active"><a href="index.php?sel=sessions"><div class="magnifier-large" id="slide-control_0" style="opacity: 0.3; ">;</div></a></span>
  <span><a href="index.php?sel=mp"><div class="lightbulb-large" id="slide-control_1" style="opacity: 0.3; ">;</div></a></span>
  <span><a href="index.php?sel=laws"><div class="topic-large" id="slide-control_2" style="opacity: 0.3; ">;</div></a></span>
<!--  <span><div class="unlock-large" id="slide-control_3" style="opacity: 0.3; ">;</div></span> -->
</div>
 <?php } ?>


<?php include "specFiles/".$include_file.".php"; ?>




<?php
  /**
   * dirty hack to ask for login
   */
  
   // } // end if

/**
 * SHOW DIRT
 **/
$showDirt=0;
if ($showDirt==1) {
  print '<div style="background:#eee; width: 300px; left: 1100px; top: 420px; height:400px; overflow:scroll; border: 1px solid; position:absolute; z-index:1000; opacity:.9; font-size:9px;">';
  echo $includeFile . ' - ' . $level;
  echo "<pre>";
  print_r($_SESSION);
  print_r($_POST);
  print_r($_SERVER);
  //print "<BR>";
  print_r($_GET);
  echo "</pre>";
  echo "</div>";
}
?>




</div> <!-- page-wrapper -->
<!-- FOOTER -->
<footer class="main-footer-wrap">
  <div class="main-footer"><a style="border:none;text-decoration:none;" href="http://www.populari.org" target="_BLANK"><span style="position:absolute;right:40px;border:none;text-decoration:none;"><img style="height:30px;border:none;" src="css/images/populari_logo.png" \></span></a>
    <nav class="footer-nav">
      <ul class="nav-list">
<!--        <li class="nav-item"><a href="#">News</a></li>
        <li class="nav-item"><a href="#">Privacy</a></li>
        <li class="nav-item"><a href="#">Pricing</a></li>
        <li class="nav-item"><a href="#">Security</a></li>
        <li class="nav-item"><a href="#">Legal</a></li>
        <li class="nav-item"><a href="#">About</a></li>
        <li class="nav-item"><a href="#">Help</a></li>
        <li class="nav-item"><a href="#">Support Searchlight</a></li>
        <li class="nav-item"><a href="?sel=logout">Log Out</a></li>
-->
        <li class="nav-item"><a href="http://www.populari.org" target="_BLANK">Think Tank Populari</a></li>
      </ul>
      <p class="copyright">© 2012 Think Tank Populari. All rights reserved.</p>
    </nav>
  </div>
</footer>

<div style="position:absolute;left:-600px;top:-636px;"><img src="searchlight.png" \></div>

</body>
</html>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-38771867-2', 'populari.org');
  ga('send', 'pageview');

</script>