<?php include "specFiles/timeline.php"; 
  echo '<link href="css/home.css" rel="stylesheet" type="text/css">';
?>

<!--INNER-->
<div class="mp_list_wrapper">
	<div class="mp_list" style="margin-left:200px;">
  	<ul class="blocks clearfix"><?php



    $query = "SELECT *,DATE_FORMAT(dob,'%d/%m/%Y') AS dob_f FROM gui_mp_in_mandate";

$query .= " WHERE 1=1";
if ($_GET['m']==2 || $GET['m']==4) $query .= " AND mandate_date_end >= DATE_FORMAT(NOW(),'%Y-%m-%d')";

if (!isset($_GET['search'])) $query .= " AND parliament_id = '".$_GET['p']."' AND mandate_id = '".$_GET['m']."'";
else {
  if (isset($_GET['p']) && $_GET['p'] > 0) $query .= " AND parliament_id = '".$_GET['p']."'";
  if (isset($_GET['m']) && $_GET['m'] > 0) $query .= " AND mandate_id = '".$_GET['m']."'";
  if (strlen($_GET['search']) > 0) $query .= " AND first_name like '%".$_GET['search']."%' || last_name like '%".$_GET['search']."%' || entity like '%".$_GET['search']."%'  || party like '%".$_GET['search']."%'";
}
$query .= " GROUP BY screen_name ORDER BY parliament_id, mandate_id DESC, screen_name ASC";

//echo $query;
			$result = mysql_query($query) or die(mysql_error());
			
			$i=0;
			$js_data = array();
			while ($data = mysql_fetch_assoc($result)) {
				
				$data = array_map("urldecode_data", $data);
				$data['img'] = 'files/member/'.strtolower($data['screen_name']).'.jpg';
				if (!is_file($data['img'])) $data['img'] = 'files/member/default_'.($data['gender']=='M'?'male':'female').'.gif';
				$js_data[$data['id']] = $data;

				echo '<li class="block'.($i % 4 == 0 ? ' first':'').'" style="opacity: 1;">';
				echo '<a class="block-img">';
				//href="javascript:openTimeline('.$data['id'].')"
				echo '<div class="img-container" style="overflow:hidden;"><img src="'.$data['img'].'" style="width:220px;height:287px;top:0;"/></div>';

				/*
				  echo '<span class="progress-bar">';
				  echo '<span class="bar" style="width:'.($data['mp_participation_pct']*100).'%;"></span>';
				  echo '<span class="perc">Prisustvo: '.($data['mp_participation_pct']*100).'% <span class="inner">['.$data['mp_count_present_sessions'].'/'.$data['mp_total_sessions'].' sjednica]</span></span>';
				  echo '</span>';
				*/

				echo '<div class="info">';
						
				echo '<h3>'.$data['first_name'].'</h3>';
				echo '<h3 style="    border-bottom: 1px solid #efae3e;">'.$data['last_name'].'</h3>';
						
				echo '<div class="meta">';
				echo '<h5 class="box-left"><span>'.$data['party'].'</span></h5>';
				echo '<h5 class="box-right"><span>'.$data['entity'];
				if ($_GET['p']==1) echo ': '.$data['electorate'];
				echo '</span></h5>';
				echo '</div>';

				echo '<h3 style="margin-top:10px;font-size:11px;text-transform:lowercase;z-index:999;">'.normalize_str($data['first_name']).'.'.normalize_str($data['last_name']).'@parliament.ba</h3>';
				echo '</div>';
				echo '</a>';
				echo '</li>';
				
				$i++;
			} // end while
		?></ul>
  	<div class="clearer"></div>
    <script language="javascript">
			var mp_in_mandate = <?php echo json_encode($js_data); ?>;
		</script>
	</div>
</div>
<!-- END INNER -->

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-38771867-2', 'populari.org');
  ga('send', 'pageview');

</script>