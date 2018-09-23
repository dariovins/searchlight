<?php
if (isset($_GET['cat'])) {

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
    	<h4>EU Policy Area:</h4>
			<h3>Agriculture</h3>
    	
      <?php
			$gazzete_publication_group_id = get_info_gazette_publication($agenda['agenda_gazette_publication_id'],'gazette_publication_group_id');
			
			if ($gazzete_publication_group_id) {
	      echo '<h4>Osnovni Zakon:</h4>';
      	echo '<h3><a href="index.php?sel=laws&view='.$gazzete_publication_group_id.'">'.get_info_gazette_publication_group($gazzete_publication_group_id,'title').'</a></h3>';
			}
			
			$agenda = get_gui_table("agenda","agenda_gazette_publication_id",$agenda['agenda_gazette_publication_id']);
			
			$agenda_list = '';
			
			if($agenda===false) {
				$agenda_list = '<div>No Data</div>';
			} else { 
				foreach ($agenda as $key=>$record) {
					
					$record['title'] = get_info_gazette_publication($law_id,"title");
					
					$js_agenda[$record['agenda_id']] = $record;
					
					$agenda_list .= '<a href="javascript:void(0)" onclick="openAgenda('.$record['agenda_id'].');return false;" title="'.$record['title'].'">';
						$agenda_list .= '<div>';
						
						$agenda_list .= '<span style="width:100px;float:left;">';
						$agenda_list .= '<h4>'.$record['session_date'].'</h4>';
						$agenda_list .= '</span><span style="float:left;width:680px;margin-bottom:10px;" title="'.$record['title'].'">';
						$agenda_list .= '<h4>'.get_info_session($record['session_id'],'title').'</h4>';
						$agenda_list .= $record['agenda_no'].') '.substr($record['title'],0,230).(strlen($record['title']) >= 230 ? '...':'').'</span>';
						
						$agenda_list .= '</div>';
					$agenda_list .= '</a>';
					
					if ($record['parliament_id'] == 1) $law_date = $record['session_date'];
					
					$js_parliament[$record['parliament_id']] = $record['parliament_title'];
					
					foreach ($js_meta_vote_type as $k => $v) {
						if (!isset($js_chart_data[$record['parliament_id']][$k]['fbih'])) $js_chart_data[$record['parliament_id']][$k]['fbih'] = 0;
						if (!isset($js_chart_data[$record['parliament_id']][$k]['rs'])) $js_chart_data[$record['parliament_id']][$k]['rs'] = 0;
						if (!isset($js_categories[$record['parliament_id']][$k])) $js_categories[$record['parliament_id']][$k] = 0;
						
						$js_chart_data[$record['parliament_id']][$k]['fbih'] += $record['vote_count_entity_type_'.$k.'_fbih'];
						$js_chart_data[$record['parliament_id']][$k]['rs'] += $record['vote_count_entity_type_'.$k.'_rs'];
						
						$js_categories[$record['parliament_id']][$k] += $record['agenda_vote_count_type_'.$k];
					}
				}
				
				$agenda_list .= '<script language="javascript"> var agenda = '.json_encode($js_agenda).'; </script>';
				
				unset($agenda);
				unset($js_agenda);
			?>
      <h4>Pregled rasprava u Parlamentu</h4>
      <h3>TBD</h3>
    </div>
    <div class="a65">
	    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
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
		
		$query = "SELECT id,title FROM meta_vote_type";
		$query .= " ORDER BY title ASC";
		$result = mysql_query($query) or die(mysql_error());
		
		$js_meta_vote_type = array();
		while ($data = mysql_fetch_assoc($result)) {
			$data = array_map("urldecode_data", $data);
			$js_meta_vote_type[$data['id']] = $data['title'];
		}
		
		$agenda = get_gui_table("agenda","agenda_gazette_publication_id",$law_id);
		
		$js_agenda = array();
		$agenda_list = '';
		
		$law_date = "";
		
		$js_chart_categories = array();
		$js_chart_data = array();
		$js_parliament = array();
		
		if($agenda===false) {
			$agenda_list = '<div>No Data</div>';
		} else { 
			
			$title = get_info_gazette_publication($law_id,"title");
			
			foreach ($agenda as $key=>$record) {
				
				$js_agenda[$record['agenda_id']] = $record;
				
				$agenda_list .= '<a href="javascript:void(0)" onclick="openAgenda('.$record['agenda_id'].');return false;" title="'.$record['title'].'">';
					$agenda_list .= '<div>';
					
					$agenda_list .= '<span style="width:100px;float:left;">';
					$agenda_list .= '<h4>'.$record['session_date'].'</h4>';
					$agenda_list .= '</span><span style="float:left;width:680px;margin-bottom:10px;" title="'.$record['title'].'">';
					$agenda_list .= '<h4>'.get_info_session($record['session_id'],'title').'</h4>';
					$agenda_list .= $record['agenda_no'].') '.substr($record['title'],0,230).(strlen($record['title']) >= 230 ? '...':'').'</span>';
					$agenda_list .= '</div>';
				$agenda_list .= '</a>';
				
				if ($record['parliament_id'] == 1) $law_date = $record['session_date'];
				
				$js_parliament[$record['parliament_id']] = $record['parliament_title'];
				
				foreach ($js_meta_vote_type as $k => $v) {
					if (!isset($js_chart_data[$record['parliament_id']][$k]['fbih'])) $js_chart_data[$record['parliament_id']][$k]['fbih'] = 0;
					if (!isset($js_chart_data[$record['parliament_id']][$k]['rs'])) $js_chart_data[$record['parliament_id']][$k]['rs'] = 0;
					if (!isset($js_categories[$record['parliament_id']][$k])) $js_categories[$record['parliament_id']][$k] = 0;
					
					$js_chart_data[$record['parliament_id']][$k]['fbih'] += $record['vote_count_entity_type_'.$k.'_fbih'];
					$js_chart_data[$record['parliament_id']][$k]['rs'] += $record['vote_count_entity_type_'.$k.'_rs'];
					
					$js_categories[$record['parliament_id']][$k] += $record['agenda_vote_count_type_'.$k];
				}
			}
			
			$agenda_list .= '<script language="javascript"> var agenda = '.json_encode($js_agenda).'; </script>';
			
			unset($agenda);
			unset($js_agenda);
		}
		?>
		<div class="a100" style="border-bottom: #ccc 1px solid;min-height:230px;margin-bottom:10px;padding:0;">
		
      <div class="a30" style="margin:20px;">
      	<h4>Datum usvajanja:</h4>
        <h3><?php echo $law_date; ?></h3>
      	
        <h4>Objavljen</h4>
        <h3 style="margin:0">Sluzbeni Glasnik List BiH</h3>
        <h5>Datum: 30/11/2010</h5>
        <h5>Godina: XIV</h4>
        <h5>Broj: 129</h4>
        @:<a href="#">link</a>
      	
        <h4>Tekst ove verzije zakona:</h4>
        <h3>Klinki da otvoris zakon</h3>
      </div>
			
			<script language="javascript">
      
          $(document).ready(function() {
              
              var colors = Highcharts.getOptions().colors;
              var categories = <?php echo json_encode($js_meta_vote_type); ?>;
              var parliament = <?php echo json_encode($js_parliament); ?>;
              var entity = ['FBIH','RS'];
              
              var categories_data = <?php echo json_encode($js_categories); ?>;
              var chart_data = <?php echo json_encode($js_chart_data); ?>;
              
              // Build the data arrays
              var browserData = [];
              var versionsData = [];
              var x = 0;
              for (var i in parliament) {
                x = 0;
                if (browserData[i] == null)  browserData[i] = [];
                if (versionsData[i] == null)  versionsData[i] = [];
                for (var j in categories) {
                  
                  // add browser data
                  browserData[i].push({
                    name: categories[j],
                    y: categories_data[i][j],
                    color: colors[x]
                  });
                  
                  var brightness = 0.2 - (0 / 2) / 5;
                  versionsData[i].push({
                    name: entity[0],
                    y: chart_data[i][j].fbih,
                    color: Highcharts.Color(colors[x]).brighten(brightness).get()
                  });
                  
                  var brightness = 0.2 - (1 / 2) / 5;
                  versionsData[i].push({
                    name: entity[1],
                    y: chart_data[i][j].rs,
                    color: Highcharts.Color(colors[x]).brighten(brightness).get()
                  });
									
									x++;
                }
								
								if (i == 1) {
									var chart1 = new Highcharts.Chart({
										chart: {
											renderTo: 'container',
											type: 'pie'
										},
										title: {
											text: 'Predstavnicki dom PSBIH'
										},
										subtitle: {
											text: 'Pregled glasanja'
										},
										plotOptions: {
											pie: {
												shadow: false
											}
										},
										series: [{
											name: 'Poslanici',
											data: browserData[i],
											size: '80%',
											dataLabels: {
												formatter: function() {
													return this.y > 0 ? this.point.name : null;
												},
												color: 'white',
												distance: -40
											}
										}, {
											name: 'Poslanici',
											data: versionsData[i],
											innerSize: '100%',
											dataLabels: {
												formatter: function() {
													// display only if larger than 1
													return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
												}
											}
										}]
									});
								} else if (i == 2) {
									var chart2 = new Highcharts.Chart({
										chart: {
											renderTo: 'container1',
											type: 'pie'
										},
										title: {
											text: 'Dom Naroda PSBIH'
										},
										subtitle: {
											text: 'Pregled glasanja'
										},
										plotOptions: {
											pie: {
												shadow: false
											}
										},
										series: [{
											name: 'Poslanici',
											data: browserData[i],
											size: '80%',
											dataLabels: {
												formatter: function() {
													return this.y > 0 ? this.point.name : null;
												},
												color: 'white',
												distance: -40
											}
										}, {
											name: 'Poslanici',
											data: versionsData[i],
											innerSize: '100%',
											dataLabels: {
												formatter: function() {
													// display only if larger than 1
													return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
												}
											}
										}]
									});
								}
              }
                            
          });
      	
      </script>
			<div class="a60" style="height:100%;">
				<div style="float:right;position:relative;margin-right:20px;">
					<div id="container" style="position:relative;width:200px;height:200px;margin: 0 auto"></div>
				</div>
				
				<div style="float:right;position:relative;margin-right:20px;">
					<div id="container1" style="position:relative;width:200px;height:200px;margin: 0 auto"></div>
  		  </div>
	    </div>
      
		</div>

		<div class="a35" style="clear:both;">
			
  	  <h4>Trajanje procedure</h4>
	    <h3>70 dana</h3>
			
  	  <h4>Broj sjednica</h4>
	    <h3>7</h3>
			
  	  <h4>TImeline</h4>
	    <h3>TBD</h3>
    	
		</div>
		
    <div class="a65">
			
	    <h3 style="margin:0">Pregled rasprava u Parlamentu o ovom zakonu</h3>
  	  <h5 style="margin:10px">Klikni na tacku dnevnog reda za vise info</h5>
			
			<?php echo $agenda_list; ?>
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
		
    echo '<div class="a70 border-right">';
    	echo '<div class="title">Present</div>';
			
			for ($j = 0; $j < sizeof($mps_in_session); $j++) {
				
				$background = '#000';//default
				$font_color = '#fff';//default
				
				$vote = get_one_vote($agenda_id,$mps_in_session[$j]['mmp_id']);
				
				if (!empty($vote['vote_bckg_hex_color'])) $background = $vote['vote_bckg_hex_color'];
				if (!empty($vote['vote_font_hex_color'])) $font_color = $vote['vote_font_hex_color'];
				
				echo'<div class="mp_box"'.($mps_in_session[$j]['mp_id']==$session['session_chair_mp_id'] ? ' title="Chairman"':'').'><img src="files/member/'.strtolower($mps_in_session[$j]['screen_name']).'.jpg"><div class="tag" style="background:'.$background.';color:'.$font_color.';">'.$mps_in_session[$j]['first_name'].'<br />'.$mps_in_session[$j]['last_name'].'</div></div>';
				
			}
			
	    echo '</div>';
			
	    echo '<div class="a30 clearer right">';
			
  	  echo '<div class="title">Voting Summary</div>';
			
    	if (!isset($vote_types)) $vote_types = get_vote_types();
			
			
			echo '<div id="container" style="min-width: 200px; height: 400px; margin: 0 auto"></div>';
			
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
					
					unset($vote);
					unset($background);
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
					categories: chart_categories,
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
        series: [{
        	name: 'SNSD',
					data: [10, 3, 5, 2, 2]
				}, {
        	name: 'HDZ',
        	data: [1, 5, 9, 4, 6]
        }, {
        	name: 'SDA',
        	data: [3, 9, 4, 7, 3]
        }]
    	});
    </script>
    <?php
		
    break;
	case "documents":
		break;
  case "laws":
    break;
  } // end switch
	
} else { ?>
<script language="javascript">
	<?php if ($_GET['sel'] == "laws") { ?>
	var Button1 = "Lista sjednica";
	var Button2 = "Nazad na listu sjednica";
	<?php } ?>
	
	$(document).ready(function(){
		
		<?php if ($_GET['sel'] == "sessions") { ?>
			var dialogButtons = {
				"Pregled": function() {
					openSessionTab('summary');
				},
				"Debata": function() {
					openSessionTab('debate');
				},
				"Glasanje": function() {
					openSessionTab('voting');
				},
				"Dokumenti": function() {
					openSessionTab('documents');
				},
				"Vezani zakoni": function() {
					openSessionTab('laws');
					
				}
			};
		<?php } else if ($_GET['sel'] == "topics") { ?>
			var dialogButtons = {
				"Pregled": function() {
					openSessionTab('themeInfo');
				}
			};
		<?php } else if ($_GET['sel'] == "laws") { ?>
			var dialogButtons = [
				{
					text: Button1,
					click: function() {
						$(".popup_info_button").button({ icons: { primary: '' }, label: Button1 });
						$(".popup_summary_button,.popup_debate_button,.popup_voting_button,.popup_documents_button").button("disable");
						openSessionTab('lawinfo');
					},
					class: 'popup_info_button'
				},
				{
					text: "Summary",
					click: function() {
						openSessionTab('summary');
					},
					class: 'popup_summary_button'
				},
				{
					text: "Debate",
					click: function() {
						openSessionTab('debate');
					},
					class: 'popup_debate_button'
				},
				{
					text: "Voting",
					click: function() {
						openSessionTab('voting');
					},
					class: 'popup_voting_button'
				},
				{
					text: "Documents",
					click: function() {
						openSessionTab('documents');
					},
					class: 'popup_documents_button'
				}
			];
		<?php } ?>
		
		$("#session").dialog({
    	autoOpen: false,
			width: 850,
			height:540,
			resizable:false,
			modal: true,
			buttons: dialogButtons,
			open: function(){
				$('.ui-widget-overlay').bind('click', function () { $(this).siblings('.ui-dialog').find('.ui-dialog-content').dialog('close'); });
				
				<?php if ($_GET['sel'] == "laws") { ?>
				$(".popup_info_button").button({ icons: { primary: '' }, label: Button1 });
				$(".popup_summary_button,.popup_debate_button,.popup_voting_button,.popup_documents_button").button("disable");
				<?php } ?>
      	$("body").css("overflow", "hidden");
    	},
    	close: function(){
  	  	$("body").css("overflow", "auto");
	    }
		});
		
	});
	
	function openAgenda(id) {
		var data = agenda[id];
		
		<?php if ($_GET['sel'] == "laws") { ?>
		$(".popup_info_button").button({ icons: { primary: 'ui-icon-circle-triangle-w' }, label: Button2 });
		$(".popup_debate_button,.popup_voting_button,.popup_documents_button").button("enable");
		<?php } ?>
		
		$("#session").attr("session",data['session_id']);
		$("#session").attr("agenda",id);
		
		openSessionTab('summary');
		
		$("#session").dialog("option","title",'Ad. ' + data['agenda_no'] + ') ' + data['title']).dialog("open");
	}
	
	function openTheme(id) {
		var data = theme_policy_area[id];
		
		$("#session").attr("theme",id);
		
		openSessionTab('themeInfo');
		
		$("#session").dialog("option","title",data['title']).dialog("open");
	}
	

	function openLaw(id) {
		var data = gazette[id];
		
		//$("#session").attr("session",data['session_id']);
		$("#session").attr("law",id);
		
		openSessionTab('lawinfo');
		
		$("#session").dialog("option","title",data['gazette_date'] + ') ' + data['gazette_publication_title']).dialog("open");
	}
	
	function openSessionTab(page) {
		
		var dataUrl = "<?php echo (isset($_GET['p']) ? "&p=".$_GET['p']:"").(isset($_GET['m']) ? "&m=".$_GET['m']:""); ?>";
		if ($("#session").attr("session")) dataUrl += "&s=" + $("#session").attr("session");
		if ($("#session").attr("agenda")) dataUrl += "&a=" + $("#session").attr("agenda");
		if ($("#session").attr("law")) dataUrl += "&l=" + $("#session").attr("law");
		if ($("#session").attr("theme")) dataUrl += "&tap=" + $("#session").attr("theme");
		
		$.ajax({
			type: "GET",
			dataType: "html", 
			url: "members.php?sel=<?php echo $_GET['sel']; ?>&cat=" + page,
			data: dataUrl,
			scriptCharset: "utf-8",
			beforeSend: function(XMLHttpRequest){
				$("div#session > div.popup_list > div.box").html('Loading data, please wait ...');
			},
			success: function(data){
				$("div#session > div.popup_list > div.box").html(data);
				
				formatSessionTab(page);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$("div#session > div.popup_list > div.box").html('Error reading data');
			}
		});
	}
	
	function formatSessionTab(page) {
		
		if (page == "summary") {
			
			var data = agenda[$("#session").attr("agenda")];
			
			//data['agenda_gazette_publication_id'];
			
			var colors = Highcharts.getOptions().colors;
			var browserData = [];
			var versionsData = [];
			
			for (i in meta_vote_type) {
				
				t = parseInt(eval("data.agenda_vote_count_type_" + i),10);
				t_fbih = parseInt(eval("data.vote_count_entity_type_" + i + "_fbih"),10);
				t_rs = parseInt(eval("data.vote_count_entity_type_" + i + "_rs"),10);
				
				browserData.push({
					name: meta_vote_type[i],
					y: t,
					color: colors[i]
				});
				
				// add version data
				var brightness = 0.2 - (0 / 2) / 5 ;
				versionsData.push({
					name: "FBIH",
					y: t_fbih,
					color: Highcharts.Color(colors[i]).brighten(brightness).get()
				});
				
				var brightness = 0.2 - (1 / 2) / 5 ;
				versionsData.push({
					name: "RS",
					y: t_rs,
					color: Highcharts.Color(colors[i]).brighten(brightness).get()
				});
				
			}
			
			// Create the chart
			var chart = new Highcharts.Chart({
				chart: {
					renderTo: 'container',
					type: 'pie'
				},
				title: {
					text: 'Pregled glasanja za ovu tacku'
				},
				yAxis: {
					title: {
						text: 'Total percent market share'
					}
				},
				plotOptions: {
					pie: {
						shadow: false
					}
				},
				tooltip: {
					/* valueSuffix: '%' */
				},
				series: [{
					name: 'Poslanici',
					data: browserData,
					size: '60%',
					dataLabels: {
						formatter: function() {
							return this.y > 0 ? this.point.name : null;
						},
						color: 'white',
						distance: -40
					}
				}, {
					name: 'Poslanici',
					data: versionsData,
					innerSize: '60%',
					dataLabels: {
						formatter: function() {
							// display only if larger than 1
							return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
						}
					}
				}]
			});
			
		} else if (page == "themeInfo") {
			
		} else if (page == "lawinfo") {
		
		} else if (page == "voting") {
			
		}
		
	}
</script>
<div id="session" style="display:none;">
  <div class="popup_list">
    <div class="box"></div>
  </div> <!-- end popup list -->
</div>
<?php } ?>