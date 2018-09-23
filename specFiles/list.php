<?php
  echo '<link href="css/home.css" rel="stylesheet" type="text/css">';

if (isset($_GET['cat']) && strlen($_GET['cat']) > 0) {
	include "specFiles/popup_data.php";
} else {
?>
<?php include "specFiles/popup.php"; ?>
<!--INNER-->
<script language="javascript">
	$(document).ready(function(){
		
		$("ul.blocks li.block").click(function(){ 
			
			if ($(this).hasClass('first')) return false;
			if ($(this).hasClass('block-large')) return false;
			
			$("ul.blocks li.block-large").addClass('block');
			$("ul.blocks li.block-large").removeClass('block-large');
			
			$(this).removeClass('block');
			$(this).addClass('block-large');
			
			var session_id = $(this).attr("id").substr(8);
			
			openSession(session_id,1);
		});
	});
	
	var agendaLoaded = 0;
	var mpLoaded = 0;
	var mp_data = {
		'html': {
			'chairman': '',
			'present': '',
			'absent': ''
		},
		'present_total': 0,
		'absent_total': 0,
		'present': [],
		'absent': [] //gui_mp_in_mandate -> entity / party  
	}
	
	function openSession(id,tab) {
		
		$("li#session_" + id + " > div.info > div.meta > div.footer > a > span.active").removeClass('active');
		$("li#session_" + id + " > div.info > div.meta > div.footer > a:eq(" + (tab-1) + ") > span").addClass('active');
		
		if ((tab == 1 || tab == 2 || tab == 4) && agendaLoaded != id) {
			
			$.ajax({
				type: "GET",
				dataType: "json", 
				url: "members.php?sel=js_list&list=agenda&session_id=" + id,
				scriptCharset: "utf-8",
				beforeSend: function(XMLHttpRequest){
					$("li#session_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Loading data, please wait ...</div>');
				},
				success: function(data){
					agendaLoaded = id;
					agenda = data;
					
					openSession(id,tab);
					
					return;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("li#session_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Error reading data</div>');
				}
			});
			
			return;
		} 
		
		if ((tab == 1 || tab == 3) && mpLoaded != id) {
			var session_data = session[id];
			
			mp_data.html.chairman = '';
			mp_data.html.present = '';
			mp_data.html.absent = '';
			
			mp_data.present_total = 0;
			mp_data.absent_total = 0;
			mp_data.present = [];
			mp_data.absent = [];
			
			for (x in mp_in_mandate) {
				
				if (session_data['session_chair_mp_id'] == x) {
					mp_data.html.chairman += '<div class="mp_box" title="Chairman"><img src="' + mp_in_mandate[x]['img'] + '"><div class="chair_tag">C</div><div class="tag">' + mp_in_mandate[x]['first_name'] + '<br />' + mp_in_mandate[x]['last_name'] + '</div></div>';
					
					mp_data.present_total++;
					if (mp_data.present[mp_in_mandate[x]['party']] == null) mp_data.present[mp_in_mandate[x]['party']] = 0;
					mp_data.present[mp_in_mandate[x]['party']]++;
				} else if ($.inArray(mp_in_mandate[x]['mmp_id'], session_data['mps_in_session']) >= 0) {
					mp_data.html.present += '<div class="mp_box"><img src="' + mp_in_mandate[x]['img'] + '"><div class="tag">' + mp_in_mandate[x]['first_name'] + '<br />' + mp_in_mandate[x]['last_name'] + '</div></div>';
					
					mp_data.present_total++;
					if (mp_data.present[mp_in_mandate[x]['party']] == null) mp_data.present[mp_in_mandate[x]['party']] = 0;
					mp_data.present[mp_in_mandate[x]['party']]++;
				} else {
					mp_data.html.absent += '<div class="mp_box"><img src="' + mp_in_mandate[x]['img'] + '"><div class="tag">' + mp_in_mandate[x]['first_name'] + '<br />' + mp_in_mandate[x]['last_name'] + '</div></div>';
					
					mp_data.absent_total++;
					if (mp_data.absent[mp_in_mandate[x]['party']] == null) mp_data.absent[mp_in_mandate[x]['party']] = 0;
					mp_data.absent[mp_in_mandate[x]['party']]++;
				}
				
			}
			
			mpLoaded = id;
		}
		
		if (tab == 1) { //summary
			
			var session_data = session[id];
			
			var agenda_count = 0;
			var agenda_tacke = [];
			
			for (i in agenda_type) {
				agenda_tacke[i] = 0;
			}
			
			for (i in agenda) {
				agenda_count++;
				
				agenda_tacke[agenda[i].agenda_type_id]++;
			}
			
			var div1 = document.createElement("div");
			$(div1).css({"float":"left","margin":"0","padding":"5px 10px","height":"190px","width":"170px"});
			var div1sub = document.createElement("div");
			$(div1sub).css({"height":"190px","margin":"0 auto"});
			
			var div1sub_html = '<h6>Datum sjednice</h6><h5>' + session_data['session_date_f'] + '</h5><div class="separate"></div>';
			div1sub_html += '<h6>Dnevni Red:</h6><h5>' + agenda_count + '</h5>';
			div1sub_html += '<h6>Tačke</h6><div class="separate"></div>';
			div1sub_html += '<h6>U raspravi:</h6>';
			div1sub_html += '<div>';
				for (i in agenda_type) {
				  agenda_type[i] == 'Ostalo' ? null : div1sub_html += '<div style="float:left;width:70px;"><h5>' + agenda_tacke[i] + '</h5><h7 style="color:#000;">' + agenda_type[i] + '</h7></div>';
				}
			div1sub_html += '</div>';
			
			$(div1sub).html(div1sub_html);

			div1.appendChild(div1sub);
			
			var div2 = document.createElement("div");
			$(div2).css({"float":"left","margin":"0","padding":"0","height":"190px","width":"265px"});
			var div2sub = document.createElement("div");
			$(div2sub).css({"width":"265px","height":"190px","margin":"0 auto"});
			
			div2.appendChild(div2sub);
			
			/* ovdje moze biti do 70 tacaka, tako da sirina terba biti funkcija broj agendi. Npr 25px po tacki agende */
			$("li#session_" + id + " > div.info > div.meta > div.box").html("");
			$("li#session_" + id + " > div.info > div.meta > div.box").append(div1);
			$("li#session_" + id + " > div.info > div.meta > div.box").append(div2);
			

			var colors = Highcharts.getOptions().colors;
    	
			var browserData = [];
			browserData.push({ name: 'Prisutni', y: mp_data.present_total, perc: Math.round(mp_data.present_total / (mp_data.present_total + mp_data.absent_total)*100), color: colors[0] });
			browserData.push({ name: 'Odsutni', y: mp_data.absent_total, perc: Math.round(mp_data.absent_total / (mp_data.present_total + mp_data.absent_total)*100), color: colors[1] });
			
			var versionsData = [];
			
			j = 0;
			for (party in mp_data.present) {
				var brightness = 0.2 - (j / mp_data.present.length) / 5 ;
				versionsData.push({
					name: party,
					y: mp_data.present[party],
					color: Highcharts.Color(colors[0]).brighten(brightness).get()
				});
			}
			
			j = 0;
			for (party in mp_data.absent) {
				var brightness = 0.2 - (j / mp_data.absent.length) / 5 ;
				versionsData.push({
					name: party,
					y: mp_data.absent[party],
					color: Highcharts.Color(colors[1]).brighten(brightness).get()
				});
			}
    	

			// Create the chart
			var chart1 = new Highcharts.Chart({
				chart: {
					renderTo: div2sub,
					type: 'pie'
				},
				credits: {
					enabled:false
				},
				title: {
					text: null
				},
				yAxis: {
					title: {
						text: ''
					}
				},
				plotOptions: {
					pie: {
						shadow: false
					}
				},
				tooltip: {
					/*valueSuffix: '%' */
				},
				series: [{
					name: 'Poslanici',
					data: browserData,
					size: '90%',
					dataLabels: {
						formatter: function() {
							return this.point.name + "<br /><b>" + this.point.perc + "%</b>";
						},
						color: 'white',
						distance: -40
					}
				}, {
					name: 'Poslanika',
					data: versionsData,
					innerSize: '95%',
					dataLabels: {
						formatter: function() {
				    	return this.y > 1 ? '<b>' + this.point.name + ': ' + this.y + '<./b>' : null;
				  	},
						distance: 18
					}
				}]
			});


		} else if (tab == 4) { //voting

			var summary_categories = [];
			var summary_series = [];
			
			for (i in meta_vote_type) {
				summary_series.push({ name: meta_vote_type[i], data: [] });
			}
			var x = 0;
			var y = 0;
			var t = 0;
			for (i in agenda) {
				x++;
				summary_categories.push('Ad.'+x);
				
				y = 0;
				for (j in meta_vote_type) {
					t = parseInt(eval("agenda[i].agenda_vote_count_type_" + j),10);
					summary_series[y].data.push(t);
					y++;
				}
			}
			
			var div2 = document.createElement("div");
			$(div2).css({"float":"left","margin":"0","padding":"0","height":"180px","overflow":"auto","overflow-y":"hidden","-ms-overflow-y":"hidden"});
			var div2sub = document.createElement("div");
			$(div2sub).css({"width":(summary_categories.length*40) + "px","min-width":"440px","height":"190px","margin":"0 auto"});
			
			div2.appendChild(div2sub);
			
			/* ovdje moze biti do 70 tacaka, tako da sirina terba biti funkcija broj agendi. Npr 25px po tacki agende */
			$("li#session_" + id + " > div.info > div.meta > div.box").html("");
			$("li#session_" + id + " > div.info > div.meta > div.box").append(div2);

			var chart2 = new Highcharts.Chart({
				chart: {
					renderTo: div2sub,
					type: 'column'
				},
				credits: {
					enabled:false
				},
				title: {
					text: '.',
					margin:2
				},

        xAxis: {
			    categories: summary_categories,
          labels: {
	          formatter: function() {
  	          return this.value;
    	      }
				  }
        },
				yAxis: {
					min: 0,
					title: {
						text: 'Glasanje po tackama'
					},
					stackLabels: {
						enabled: true,
			  	style: {
				        fontWeight: 'bold',
				        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
						}
					}
				},
				legend: {
					align: 'left',
					x: 50,
					verticalAlign: 'top',
					y: -10,
					floating: true,
					backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
					borderColor: '#CCC',
					borderWidth: 0,
					shadow: false
				},
				tooltip: {
					formatter: function() {
						return '<b>'+ this.series.name +':</b>'+this.y +'<br/>';
					}
				},
				plotOptions: {
					column: {
						stacking: 'normal',
						dataLabels: {
							enabled: false,
							color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
						}
					}
				},
				series: summary_series
			});
			
		} else if (tab == 2) { //agenda
			
			var box_html = '';
			
			if (agenda.length == 0) { //ovo ne radi
				var box_html = '<div class="a100">No Data</div>';
			} else {
				
				for (i in agenda) {
					box_html += '<a href="javascript:void(0)" onclick="openAgenda(' + agenda[i]['agenda_id'] + ');return false;" title="' + agenda[i]['agenda_id'] + agenda[i]['title'] + '">';
						box_html += '<div class="box a100">';
							box_html += '<span style="width:20px;float:left;">' + agenda[i]['agenda_no'] + ')</span><span style="float:left;width:390px;">' + agenda[i]['title'].substr(0,130) + (agenda[i]['title'].length >= 130 ? '...':'') + '</span>';
						box_html += '</div>';
					box_html += '</a>';
				}
			}
			
			$("li#session_" + id + " > div.info > div.meta > div.box").html(box_html);
			
		} else if (tab == 3) { //mps in session
			
			var session_data = session[id];
			
			var box_html = '';
			
			box_html += '<div class="mp_list a70">';
				box_html += '<div class="title70">Present</div>';
					
				box_html += mp_data.html.chairman;
				box_html += mp_data.html.present;
				
			box_html += '</div>';
			
			box_html += '<div class="mp_list a30" style="color:#000">';
				box_html += '<div class="title30">Absent</div>';
				
				box_html += mp_data.html.absent;
				
			box_html += '</div>';
			
			$("li#session_" + id + " > div.info > div.meta > div.box").html(box_html);
		}
		
		return false;
	}
</script>





<div class="block_list_wrapper">
	<div class="block_list" style="margin-left:200px;">
		<ul class="blocks clearfix"><?php
			
			echo '<li class="block first">';
				echo '<div class="info">';
					echo '<h3 style="padding:10px 20px;">Lista<br /><br />Sjednica</h3>';
				echo '</div>';
			echo '</li>';
			
		  $query = "SELECT id,title FROM meta_vote_type";
			$query .= " ORDER BY title ASC";
			$result = mysql_query($query) or die(mysql_error());
			
			$js_meta_vote_type = array();
			while ($data = mysql_fetch_assoc($result)) {
				
				$data = array_map("urldecode_data", $data);
				$js_meta_vote_type[$data['id']] = $data['title'];
			}
			
		  $query = "SELECT id,title FROM meta_agenda_type";
			$query .= " ORDER BY title ASC";
			$result = mysql_query($query) or die(mysql_error());
			
			$js_agenda_type = array();
			while ($data = mysql_fetch_assoc($result)) {
				
				$data = array_map("urldecode_data", $data);
				$js_agenda_type[$data['id']] = $data['title'];
			}
			
		  $query = "SELECT *,DATE_FORMAT(dob,'%d/%m/%Y') AS dob_f FROM gui_mp_in_mandate";
			$query .= " WHERE parliament_id = '".$_GET['p']."' AND mandate_id = '".$_GET['m']."'";
			$query .= " ORDER BY screen_name ASC";
			$result = mysql_query($query) or die(mysql_error());
			
			$js_mp_in_mandate = array();
			while ($data = mysql_fetch_assoc($result)) {
				
				$data = array_map("urldecode_data", $data);
				$data['img'] = 'files/member/'.strtolower($data['screen_name']).'.jpg';
				$js_mp_in_mandate[$data['mp_id']] = $data;
			}
			

			
$query = "SELECT *,DATE_FORMAT(session_date,'%d.%m.%Y.') AS session_date_f FROM gui_session";
if (isset($_GET['search']) && strlen($_GET['search']) > 0) $query .= ", agenda";
$query .= " WHERE 1=1";
if (isset($_GET['p']) && $_GET['p'] > 0) $query .= " AND gui_session.parliament_id = '".$_GET['p']."'";
if (isset($_GET['m']) && $_GET['m'] > 0) $query .= " AND gui_session.mandate_id = '".$_GET['m']."'";
if (isset($_GET['search']) && strlen($_GET['search']) > 0) $query .= " AND agenda.session_id=gui_session.session_id AND agenda.title_normalized like '%".str_replace(" ","%",$_GET['search'])."%'";
if (isset($_GET['search']) && strlen($_GET['search']) > 0) $query .= " GROUP BY gui_session.session_id";
$query .= " ORDER BY session_date ASC";
			$result = mysql_query($query) or die(mysql_error());
			
			$js_session = array();
			while ($data = mysql_fetch_assoc($result)) {
				
				$data = array_map("urldecode_data", $data);
				
				$mps_in_session = array();
				
				$query_mps = "SELECT mmp_id FROM gui_mp_in_session WHERE session_id = '".$data['session_id']."' ORDER BY mmp_id ASC";
				$result_mps = mysql_query($query_mps) or die(mysql_error());
				while ($data_mps = mysql_fetch_row($result_mps)) {
					$mps_in_session[] = $data_mps[0];
				}
				$data['mps_in_session'] = $mps_in_session;
				
				$js_session[$data['session_id']] = $data;
				
				echo '<li class="block" id="session_'.$data['session_id'].'">';
				echo '<div class="info">';
				echo '<div class="meta" style="position:relative;">';
				echo '<h2 style="position:absolute;width:40px;text-align:right;">'.$data['session_no'].'</h2>';
				echo '<h4 style="position:absolute;height:60px;width:100px;left:60px;top:0;"><span style="position:absolute;bottom:0">'.$data['session_title'].'</span></h4>';
				//velika kocka
				echo '<div class="subtitle"><h3>'.get_info_session($data['session_id'],'title').'</h3></div>';
							
				echo '<div class="box">';
				echo '</div>';
							
				echo '<div class="footer">';
				echo '<a href="javascript:void(0)" onclick="openSession('.$data['session_id'].',1);return false;"><span>Pregled</span></a>';
				echo '<a href="javascript:void(0)" onclick="openSession('.$data['session_id'].',2);return false;"><span>Dnevni Red</span></a>';
				echo '<a href="javascript:void(0)" onclick="openSession('.$data['session_id'].',3);return false;"><span>Poslanici</span></a>';
				echo '<a href="javascript:void(0)" onclick="openSession('.$data['session_id'].',4);return false;"><span>Glasanje</span></a>';
				echo '</div>';
				//kraj velika kocka
				echo '</div>';
						
				echo '<span class="bottom-tag"><span class="perc">'.$data['session_date_f'].'</span></span>';
				//kraj samo za kocku
						
				echo '</div>'; //info
				echo '</li>';
			}
		?></ul>
  		<div class="clearer"></div>
		<script language="javascript">
		  var mp_in_mandate = <?php echo json_encode($js_mp_in_mandate); ?>;
		  var session = <?php echo json_encode($js_session); ?>;
		  var agenda = [];
		  var meta_vote_type = <?php echo json_encode($js_meta_vote_type); ?>;
		  var agenda_type = <?php echo json_encode($js_agenda_type); ?>;
		</script>
	</div>
</div>
<!-- END INNER -->


<?php } ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-38771867-2', 'populari.org');
  ga('send', 'pageview');

</script>