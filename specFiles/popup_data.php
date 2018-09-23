<?php
	$parliament_id 	= isset($_GET['p']) ? $_GET['p']:0;
	$mandate_id 		= isset($_GET['m']) ? $_GET['m']:0;
	$session_id 		= isset($_GET['s']) ? $_GET['s']:0;
	$agenda_id 			= isset($_GET['a']) ? $_GET['a']:0;
	$law_id 				= isset($_GET['l']) ? $_GET['l']:0;
	$tap_id 				= isset($_GET['tap']) ? $_GET['tap']:0;
	
	
	switch($_GET['cat']) {
	case 'summary':
		
		$agenda=get_one_gui_table("agenda","agenda_id",$agenda_id);
		?>
    <div class="a35">
    	
      <?php
			$gazzete_publication_group_id = get_info_gazette_publication($agenda['agenda_gazette_publication_id'],'gazette_publication_group_id');
			
			if ($gazzete_publication_group_id) {
	      echo '<h4>Osnovni Zakon:</h4>';
      	echo '<h3><a href="index.php?sel=laws&view='.$gazzete_publication_group_id.'">'.get_info_gazette_publication_group($gazzete_publication_group_id,'title').'</a></h3>';
			}
			?>
      <h4>Pregled rasprava u Parlamentu</h4>
      <h3 class="pregled_rasprava"></h3>
    </div>
    <div class="a65">
	 <div id="container" style="min-width: 400px; height:400px; margin: 0 auto"></div>
    </div>
		<?php
	  break;
	case 'themeInfo':
		?>
		<script language="javascript">
	  	$(document).ready(function() {
	    	$(".a60").html(theme_policy_area[<?php echo $tap_id; ?>]['komentar']);
			});
		</script>
		<div class="a100" style="border-bottom: #ccc 1px solid;min-height:230px;margin-bottom:10px;padding:0;">
      <div class="a30" style="margin:20px;">
         <h4>Datum usvajanja:</h4>
         <h3>20/11/2010</h3>
      
         <h4>Objavljen</h4>
         <h3 style="margin:0">Sluzbeni Glasnik List BiH</h3>
         <h5>Datum: 30/11/2010</h5>
         <h5>Godina: XIV</h4>
         <h5>Broj: 129</h4>
         @:<a href="#">link</a>
      
         <h4>Tekst ove verzije zakona:</h4>
         <h3>Klinki da otvoris zakon</h3>
      </div>
      
      <div class="a60" style="height:100%;"></div>
		</div>
		<?php
	  break;
		
	case 'lawinfo':
		?>
		<div class="a100" style="border-bottom: #ccc 1px solid;min-height:230px;margin-bottom:10px;padding:0;">
		
      <div class="a30" style="margin:20px;">
      	<h4>Datum usvajanja:</h4>
        <h3 class="law_date"></h3>
      	
        <h4>Objavljen</h4>
        <h3 class="registry_title" style="margin:0"></h3>
        <h5>Datum: <span class="gazzete_date"></span></h5>
        <h5>Godina: <span class="gazette_year"></span></h4>
        <h5>Broj: <span class="gazzete_no"></span></h4>
        @:<span class="relURI"></span>
      	
        <h4>Tekst ove verzije zakona:</h4>
        <h3>Klinki da otvoris zakon</h3>
      </div>
			
			<div class="a60" style="height:100%;">
				<div style="float:right;position:relative;margin-right:20px;">
					<div id="lawinfo_container1" style="position:relative;width:200px;height:200px;margin: 0 auto"></div>
				</div>
				
				<div style="float:right;position:relative;margin-right:20px;">
					<div id="lawinfo_container2" style="position:relative;width:200px;height:200px;margin: 0 auto"></div>
  		  </div>
	    </div>
      
		</div>

		<div class="a35" style="clear:both;">
			
  	  <h4>Trajanje procedure</h4>
	    <h3><span class="procedure_count_days_HOR"></span> dana / <span class="procedure_count_days_HOP"></span> dana</h3>
			
  	  <h4>Broj sjednica</h4>
	    <h3><span class="procedure_count_sessions_HOR"></span> / <span class="procedure_count_sessions_HOP"></span></h3>
			
  	  <h4>Timeline</h4>
	    <div class="timeline"></div>
    	
		</div>
		
    <div class="a65">
			
	    <h3 style="margin:0">Pregled rasprava u Parlamentu o ovom zakonu</h3>
  	  <h5 style="margin:10px">Klikni na tacku dnevnog reda za vise info</h5>
			
			<div class="pregled_rasprava"></div>
		</div>
		<?php
		
		break;
	case 'debate':
		
		//$session = get_one_gui_table("session","session_id",$session_id);
		//$mp_chairman=get_one_gui_table("mp","mp_id",$session['session_chair_mp_id']);
		//$mps_in_mandate=get_gui_table("mp_in_mandate","mandate_id",$session["mandate_id"]);
		//$mps_in_session=get_gui_table("mp_in_session","session_id",$session_id);
		//$agenda=get_one_gui_table("agenda","id",$agenda_id);
    
		
    $debate = get_debates($session_id,$agenda_id,null);
		
    if ($debate===false) {
			echo 'No Data';
		} else {
			echo '<div class="a100">';
			
			foreach ($debate as $key=>$record) {
				
		    if ($record["mp_in_mandate_id"] > 0) $mp = get_one_gui_table("mp","mp_id",get_info_gui_table("mp_in_mandate","mp_id",$record['mp_in_mandate_id']));
				
		    echo '<div class="debate '.($key==0?"first":null).'">';
				
		    $background='#000';//default
		    $font_color='#fff';//default
				
		    if ($record['agenda_id']) {
					$vote=get_one_vote($record['agenda_id'],$record['mp_in_mandate_id']);
					
					if (!empty($vote['vote_bckg_hex_color'])) $background=$vote['vote_bckg_hex_color'];
					if (!empty($vote['vote_font_hex_color'])) $font_color=$vote['vote_font_hex_color'];
				}
				
		    if (!is_array($mp)) $image = 'default_male.gif';
		    else $image = strtolower($mp['screen_name']).'.jpg';
				
		    echo'<div class="mp_box" title="Chairman"><img src="files/member/'.$image.'"><div class="tag" style="background:'.$background.';color:'.$font_color.';">'.$mp['first_name'].'<br />'.$mp['last_name'].'</div></div>';
				
		    echo '<div class="a65" style="margin: 5px 150px 20px 10px;">';
			    echo '<h4 style="font-weight:normal;text-transform:none;">'.$record['debate_text'].'</h4>';
		    echo '</div>';
				
	    	echo '</div> <!-- end div -->';
				
	    	unset($mp);
			} //end foreach
			
			echo '</div> <!-- end a100 -->';
			
		} // end if
    break;
  case "voting":
		
		$session = get_one_gui_table("session","session_id",$session_id);
		//$mp_chairman=get_one_gui_table("mp","mp_id",$session['session_chair_mp_id']);
		$mps_in_mandate=get_gui_table("mp_in_mandate","mandate_id",$session["mandate_id"]);
		$mps_in_session=get_gui_table("mp_in_session","session_id",$session_id);
		$agenda=get_one_gui_table("agenda","agenda_id",$agenda_id);
		
		if (!isset($vote_types)) $vote_types = get_vote_types();
		$vote_type_count = array();
		
    echo '<div class="a70 border-right">';
    	echo '<div class="title">Present</div>';
			
			for ($j = 0; $j < sizeof($mps_in_session); $j++) {
				
				$background = '#000';//default
				$font_color = '#fff';//default
				
				$vote = get_one_vote($agenda_id,$mps_in_session[$j]['mmp_id']);
				
				if (!empty($vote['vote_bckg_hex_color'])) $background = $vote['vote_bckg_hex_color'];
				if (!empty($vote['vote_font_hex_color'])) $font_color = $vote['vote_font_hex_color'];
				
				echo'<div class="mp_box"'.($mps_in_session[$j]['mp_id']==$session['session_chair_mp_id'] ? ' title="Chairman"':'').'><img src="files/member/'.strtolower($mps_in_session[$j]['screen_name']).'.jpg"><div class="tag" style="background:'.$background.';color:'.$font_color.';">'.$mps_in_session[$j]['first_name'].'<br />'.$mps_in_session[$j]['last_name'].'</div></div>';
				
				if (!isset($vote_type_count[$mps_in_session[$j]['party']])) {
					$vote_type_count[$mps_in_session[$j]['party']] = array();
					for ($z = 0; $z < sizeof($vote_types); $z++) {
						$vote_type_count[$mps_in_session[$j]['party']][$vote_types[$z]['id']] = 0;
					}
				}
				if (isset($vote['vote_type_id'])) $vote_type_count[$mps_in_session[$j]['party']][$vote['vote_type_id']]++;

			}
			
	    echo '</div>';
			
	    echo '<div class="a30 clearer right">';

		
		$graph_categories = array();
		for ($z = 0; $z < sizeof($vote_types); $z++) {
			array_push($graph_categories,$vote_types[$z]['title']);
		}
		
		$graph_data = array();
		foreach ($vote_type_count as $key_party=>$vote_party) {
			
			$votes = array();
			for ($z = 0; $z < sizeof($vote_types); $z++) {
				array_push($votes,$vote_party[$vote_types[$z]['id']]);
			}
			
			array_push($graph_data,array('name' => $key_party,'data' => $votes));
		}
			
	    echo '<div class="title">Voting Summary</div>';
			
			echo '<div id="container" style="min-width: 200px; height:'.(count($graph_data)*60).'px; margin: 0 auto"></div>';
			
	    // total
			echo '<div style="margin:10px;width:100%;height:30px;border-bottom:1px solid #ccc;">';
				echo '<div style="width:100%;height:25px;margin-right:10px;float:left;background:#'.get_info_vote_type($agenda['agenda_vote_result'],'type_bckg_hex_color').';border:1px solid;text-align:center;"><h5 style="line-height:22px;color:#'.get_info_vote_type($agenda['agenda_vote_result'],'type_font_hex_color').';">'.get_info_vote_type($agenda['agenda_vote_result'],'title').'</h5></div>';
			echo '</div>';
			
	    echo '<div style="margin:10px;width:100%;height:30px;border-bottom:1px solid #ccc;">';
  		  echo '<div style="width:35px;height:25px;margin-right:10px;float:left;background:transparent;border:1px solid;text-align:center;"><h5 style="line-height:22px;">'.$agenda['agenda_vote_count_total'].'</h5></div>';
    		echo '<div style="float:left;width:90px;line-height:22px;">';
			    echo '<h5>Total Votes</h5>';
		    echo '</div>';
    	echo '</div>';
			
    	foreach ($vote_types as $key=>$vote_type) {
				
	      echo '<div style="margin:10px;width:100%;height:30px;">';
  		    echo '<div style="width:35px;height:25px;margin-right:10px;float:left;background:#'.$vote_type['type_bckg_hex_color'].';text-align:center;"><h5 style="color:#'.$vote_type['type_font_hex_color'].'; line-height:22px;">'.$agenda['agenda_vote_count_type_'.$vote_type['id']].'</h5></div>';
      		echo '<div style="float:left;width:90px;line-height:22px;">';
			      echo '<h5>'.$vote_type['title'].'</h5>';
      		echo '</div>';
      	echo '</div>';
	}
			
			
    echo '</div>';
		
    echo '<div class="a70 border-right">';
	    echo '<div class="title">Absent</div>';
			
  	  for ($j = 0; $j < sizeof($mps_in_mandate); $j++) {
				$found=0;
				for ($j1=0;$j1<sizeof($mps_in_session);$j1++) {
	    		if ($mps_in_mandate[$j]["mmp_id"] == $mps_in_session[$j1]["mmp_id"]) {
						$found=1;
						continue;
	      	}
	  		}
				
				if ($found==0) {
	    		$background='#000';//default
	    		$font_color='#fff';//default
					
	    		$vote=get_one_vote($agenda_id,$mps_in_mandate[$j]['mmp_id']);
					
	    		if (!empty($vote['vote_bckg_hex_color'])) $background=$vote['vote_bckg_hex_color'];
	    		if (!empty($vote['vote_font_hex_color'])) $font_color=$vote['vote_font_hex_color'];
					
	    		echo '<div class="mp_box"><img src="files/member/'.strtolower($mps_in_mandate[$j]['screen_name']).'.jpg"><div class="tag" style="background:'.$background.';color:'.$font_color.';">'.$mps_in_mandate[$j]['first_name'].'<br />'.$mps_in_mandate[$j]['last_name'].'</div></div>';
					
					if (!isset($vote_type_count[$mps_in_mandate[$j]['party']])) {
						$vote_type_count[$mps_in_mandate[$j]['party']] = array();
						for ($z = 0; $z < sizeof($vote_types); $z++) {
							$vote_type_count[$mps_in_mandate[$j]['party']][$vote_types[$z]['id']] = 0;
						}
					}
					if (isset($vote['vote_type_id'])) $vote_type_count[$mps_in_mandate[$j]['party']][$vote['vote_type_id']]++;
	  		}
			}
			
   	echo '</div>';
		
		?>
    <script language="javascript">
			var chart = new Highcharts.Chart({
      	chart: {
        	renderTo: 'container',
					type: 'bar'
				},
				title: {
					text: 'Pregled Glasanja'
				},
				subtitle: {
					text: 'po politickim partijama'
				},
				xAxis: {
					categories: <?php echo json_encode($graph_categories); ?>,
					title: {
						text: '',
					}
				},
				yAxis: {
        	min: 0,
					title: {
          	text: 'Poslanici po strankama',
            align: 'high'
          },
          labels: {
          	overflow: 'justify'
					}
				},
				tooltip: {
        	formatter: function() {
          	return this.series.name + ': ' + this.y + ' Poslanika';
					}
				},
				plotOptions: {
					bar: {
          	dataLabels: {
            	enabled: true
            }
          }
        },
        legend: {
        	layout: 'vertical',
          align: 'right',
          verticalAlign: 'top',
          x: 10,
          y: 250,
          floating: true,
          borderWidth: 1,
          backgroundColor: '#FFFFFF',
          shadow: true,
				},
        credits: {
        	enabled: false
        },
        series: <?php echo json_encode($graph_data); ?>
    	});
    </script>
    <?php
		
    break;
	case "documents":
		break;
  case "laws":
    break;
  } // end switch