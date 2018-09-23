<script language="javascript">
	var agenda_gazette = [];
	
	<?php if ($_GET['sel'] == "laws") { ?>
	var Button1 = "Lista sjednica";
	var Button2 = "Nazad na listu sjednica";
	<?php } ?>
	
	$(document).ready(function(){
		
		<?php if ($_GET['sel'] == "sessions" || $_GET['sel'] == "search" &&  $_POST['search_type'] == "search_list" ) { ?>
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
			<?php } else if ($_GET['sel'] == "laws" || $_GET['sel'] == "search" &&  $_POST['search_type'] == "search_laws" ) { ?>
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
	
	function openAgenda(id,tip) {
		if (tip == null) tip = 1;
		
		if (tip == 1) {
			var data = agenda[id];
		} else {
			var data = agenda_gazette[id];
		}
		
		<?php if ($_GET['sel'] == "laws") { ?>
		$(".popup_info_button").button({ icons: { primary: 'ui-icon-circle-triangle-w' }, label: Button2 });
		$(".popup_summary_button,.popup_debate_button,.popup_voting_button,.popup_documents_button").button("enable");
		<?php } ?>
		
		$("#session").attr("session",data['session_id']);
		$("#session").attr("agenda",id);
		$("#session").attr("agendatip",tip);
		
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
			
			if ($("#session").attr("agendatip") == 1) {
				var data = agenda[$("#session").attr("agenda")];
			} else {
				var data = agenda_gazette[$("#session").attr("agenda")];
			}
			
			if (data['agenda_gazette_publication_id'] > 0) {
				
				$.ajax({
					type: "GET",
					dataType: "json", 
					url: "members.php?sel=js_list&list=agenda&agenda_gazette_publication_id=" + data['agenda_gazette_publication_id'],
					scriptCharset: "utf-8",
					beforeSend: function(XMLHttpRequest){
						$("div#session > div.popup_list > div.box .pregled_rasprava").html('<div class="a100">Loading data, please wait ...</div>');
					},
					success: function(data){
						agenda_gazette = data;
						
						var box_html = '';
						
						if (agenda_gazette.length == 0) { //ovo ne radi
							var box_html = '<div class="a100">No Data</div>';
						} else {
							
							for (i in agenda_gazette) {
								box_html += '<a href="javascript:void(0)" onclick="openAgenda(' + agenda_gazette[i]['agenda_id'] + ',2);return false;" title="' + agenda_gazette[i]['agenda_id'] + agenda_gazette[i]['title'] + '">';
									
									box_html += '<span style="width:100px;float:left;">';
										box_html += '<h4>' + agenda_gazette[i]['session_date'] + '</h4>';
									box_html += '</span>';
									
									box_html += '<span style="float:left;width:680px;margin-bottom:10px;" title="' + agenda_gazette[i]['title'] + '">';
										box_html += '<h4>' + agenda_gazette[i]['session_title'] + '</h4>';
									box_html += agenda_gazette[i]['agenda_no'] + ') ' + agenda_gazette[i]['title'].substr(0,130) + (agenda_gazette[i]['title'].length >= 130 ? '...':'') + '</span>';
									
								box_html += '</a>';
							}
						}
						
						$("div#session > div.popup_list > div.box .pregled_rasprava").html(box_html);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						$("div#session > div.popup_list > div.box .pregled_rasprava").html('<div class="a100">Error reading data</div>');
					}
				});
				
			}
			
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
			
			var data = gazette[$("#session").attr("law")];
			
			$("div#session > div.popup_list > div.box .registry_title").html(data['registry_title']);
			$("div#session > div.popup_list > div.box .gazette_date").html(data['gazette_date']);
			$("div#session > div.popup_list > div.box .gazette_year").html(data['gazette_year']);
			$("div#session > div.popup_list > div.box .gazette_no").html(data['gazette_no']);
			
			$("div#session > div.popup_list > div.box .relURI").html('<a href="' + data['relURI'] + '">link</a>');
			
			
			$("div#session > div.popup_list > div.box .procedure_count_days_HOR").html(data['procedure_count_days_HOR']);
			$("div#session > div.popup_list > div.box .procedure_count_days_HOP").html(data['procedure_count_days_HOP']);
			
			var box_html = '';
			
			if (gazette.length == 0) {
				var box_html = '<div class="a100">No Data</div>';
			} else {
				
				for (i in gazette) {
					
					if (i == parseInt($("#session").attr("law"))) box_html += '<b>';
					
					box_html += '<a href="javascript:void(0)" onclick="openLaw(' + gazette[i]['gazette_publication_id'] + ');return false;" title="' + gazette[i]['gazette_publication_title'] + '">';
						box_html += '<div class="box a100">';
							box_html += '<span style="width:90px;float:left;">' + gazette[i]['gazette_date'] + ')</span><span style="float:left;width:320px;">' + gazette[i]['gazette_publication_title'].substr(0,130) + (gazette[i]['gazette_publication_title'].length >= 130 ? '...':'') + '</span>';
						box_html += '</div>';
					box_html += '</a>';
					
					if (i == parseInt($("#session").attr("law"))) box_html += '</b>';
				}
			}
			
			$("div#session > div.popup_list > div.box .timeline").html(box_html);
			
			if ($("#session").attr("law") > 0) {
				
				$.ajax({
					type: "GET",
					dataType: "json", 
					url: "members.php?sel=js_list&list=agenda&agenda_gazette_publication_id=" + $("#session").attr("law"),
					scriptCharset: "utf-8",
					beforeSend: function(XMLHttpRequest){
						$("div#session > div.popup_list > div.box .pregled_rasprava").html('<div class="a100">Loading data, please wait ...</div>');
					},
					success: function(data){
						agenda_gazette = data;
						
						var box_html = '';
						
						var law_date = '';
						var count_sessions_HOR = 0,count_sessions_HOP = 0;
						
						var parliament = [];
            var categories_data = [];
            var chart_data = [];
						
						if (agenda_gazette.length == 0) { //ovo ne radi
							var box_html = '<div class="a100">No Data</div>';
						} else {
							
							for (i in agenda_gazette) {
								
								if (agenda_gazette[i]['parliament_id'] == 1) law_date = agenda_gazette[i]['session_date'];
								
								if (agenda_gazette[i]['parliament_id'] == 1) {
									count_sessions_HOR++;
								} else if (agenda_gazette[i]['parliament_id'] == 2) {
									count_sessions_HOP++;
								}
								
								box_html += '<a href="javascript:void(0)" onclick="openAgenda(' + agenda_gazette[i]['agenda_id'] + ',2);return false;" title="' + agenda_gazette[i]['agenda_id'] + agenda_gazette[i]['title'] + '">';
									
									box_html += '<span style="width:100px;float:left;">';
										box_html += '<h4>' + agenda_gazette[i]['session_date'] + '</h4>';
									box_html += '</span>';
									
									box_html += '<span style="float:left;width:680px;margin-bottom:10px;" title="' + agenda_gazette[i]['title'] + '">';
										box_html += '<h4>' + agenda_gazette[i]['session_title'] + '</h4>';
									box_html += agenda_gazette[i]['agenda_no'] + ') ' + agenda_gazette[i]['title'].substr(0,130) + (agenda_gazette[i]['title'].length >= 130 ? '...':'') + '</span>';
									
								box_html += '</a>';
								
								parliament[agenda_gazette[i]['parliament_id']] = agenda_gazette[i]['parliament_title'];
								if (chart_data[agenda_gazette[i]['parliament_id']] == null) chart_data[agenda_gazette[i]['parliament_id']] = [];
								if (categories_data[agenda_gazette[i]['parliament_id']] == null) categories_data[agenda_gazette[i]['parliament_id']] = [];
								
								for (j in meta_vote_type) {
									
									if (chart_data[agenda_gazette[i]['parliament_id']][j] == null) {
										chart_data[agenda_gazette[i]['parliament_id']][j] = [];
										chart_data[agenda_gazette[i]['parliament_id']][j]['fbih'] = 0;
										chart_data[agenda_gazette[i]['parliament_id']][j]['rs'] = 0;
									}
									if (categories_data[agenda_gazette[i]['parliament_id']][j] == null) categories_data[agenda_gazette[i]['parliament_id']][j] = 0;
									
									chart_data[agenda_gazette[i]['parliament_id']][j]['fbih'] += parseInt(agenda_gazette[i]['vote_count_entity_type_' + j + '_fbih']);
									chart_data[agenda_gazette[i]['parliament_id']][j]['rs'] += parseInt(agenda_gazette[i]['vote_count_entity_type_' + j + '_rs']);
									
									categories_data[agenda_gazette[i]['parliament_id']][j] += parseInt(agenda_gazette[i]['agenda_vote_count_type_' + j]);
								}
							}
						}
						
						$("div#session > div.popup_list > div.box .pregled_rasprava").html(box_html);
						$("div#session > div.popup_list > div.box .law_date").html(law_date);
						
						$("div#session > div.popup_list > div.box .procedure_count_sessions_HOR").html(count_sessions_HOR);
						$("div#session > div.popup_list > div.box .procedure_count_sessions_HOP").html(count_sessions_HOP);
						
						//graph start
              var colors = Highcharts.getOptions().colors;
              var categories = meta_vote_type;
              var entity = ['FBIH','RS'];
              
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
								
								console.log(browserData[i]);
								console.log(versionsData[i]);
								
								
								if (i == 1) {
									var chart1 = new Highcharts.Chart({
										chart: {
											renderTo: 'lawinfo_container1',
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
											renderTo: 'lawinfo_container2',
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
						//graph end
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						$("div#session > div.popup_list > div.box .pregled_rasprava").html('<div class="a100">Error reading data</div>');
					}
				});
				
			}
			
		} else if (page == "voting") {
			
		}
		
	}
</script>
<div id="session" style="display:none;">
  <div class="popup_list">
    <div class="box"></div>
  </div> <!-- end popup list -->
</div>