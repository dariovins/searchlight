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
			
			var gazette_id = $(this).attr("id").substr(8);
			
			openGazette(gazette_id,1);
		});
		
		<?php
		if (isset($_GET['view']) && $_GET['view'] > 0) {
			echo '$("ul.blocks li.block#gazette_'.$_GET['view'].'").click();';
		}
		?>
	});
	
	
	function setChart(name, categories, data, color) {
  	chart.xAxis[0].setCategories(categories, false);
		chart.series[0].remove(false);
		chart.addSeries({
			name: name,
			data: data,
			color: color || 'white'
		}, false);
		chart.redraw();
	}
	
	var gazetteLoaded = 0;
	
	function openGazette(id,tab) {
		
		$("li#gazette_" + id + " > div.info > div.meta > div.footer > a > span.active").removeClass('active');
		$("li#gazette_" + id + " > div.info > div.meta > div.footer > a:eq(" + (tab-1) + ") > span").addClass('active');
		
		if ((tab == 1 || tab == 2) && gazetteLoaded != id) {
			
			$.ajax({
				type: "GET",
				dataType: "json", 
				url: "members.php?sel=js_list&list=gazette&gazette_publication_group_id=" + id,
				scriptCharset: "utf-8",
				beforeSend: function(XMLHttpRequest){
					$("li#gazette_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Loading data, please wait ...</div>');
				},
				success: function(data){
					
					gazetteLoaded = id;
					gazette = data;
					
					openGazette(id,tab);
					
					return;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("li#gazette_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Error reading data</div>');
				}
			});
			
			return;
		} 
		
		if (tab == 1) { //summary
		  
			var div1 = document.createElement("div");
			$(div1).css({"float":"left","margin":"0","padding":"5px 10px","height":"190px","width":"150px"});
			var div1sub = document.createElement("div");
			$(div1sub).css({"height":"190px","margin":"0 auto"});
			
			var gazette_count = 0;
			var gazette_first = "";
			var gazzete_last = "";
			
			var gazette_categories = [];
			var gazette_series_1 = [];
			var gazette_series_2 = [];
			
			for (i in gazette) {
				gazette_count++;
				
				if (gazette_count == 1) gazette_first = gazette[i]['gazette_date'];
				gazzete_last = gazette[i]['gazette_date'];
				
				gazette_categories.push(gazette[i]['gazette_date']);
				gazette_series_1.push(parseInt(gazette[i]['procedure_count_days_HOR'],10));
				gazette_series_2.push(parseInt(gazette[i]['procedure_count_days_HOP'],10));
			}
			
			// var div1sub_html = '<h6>EU Policy Area</h6><h5>' + 'Agriculture' + '</h5>';
			//div1sub_html += '<div class="separate"></div>';
			var div1sub_html = '<h6>Prva verzija zakona</h6><h5>' + gazette_first + '</h5>';
			div1sub_html += '<div class="separate"></div>';
			div1sub_html += '<h6>Zadnja verzija</h6><h5>' + gazzete_last + '</h5>';
			div1sub_html += '<div class="separate"></div>';
			div1sub_html += '<h6>Izmjena i dopuna</h6><h5>' + gazette_count + '</h5>';
			div1sub_html += '<div>';
			//			div1sub_html += '<div style="float:left;width:70px;"><h5>' + 3 + '</h5><h7 style="color:#000;">' + 'Type' + '</h7></div>';

			div1sub_html += '</div>';
			
			$(div1sub).html(div1sub_html);

			div1.appendChild(div1sub);
			
			var div2 = document.createElement("div");
			$(div2).css({"float":"left","margin":"0","padding":"0","height":"190px","width":"285px","overflow":"auto","overflow-x":"hidden","-ms-overflow-x":"hidden"});
			var div2sub = document.createElement("div");
			$(div2sub).css({"height":(4*100) + "px","min-height":"190px","width":"275px","margin":"0 auto","opacity":"0.85"});
			div2.appendChild(div2sub);
			
			/* ovdje moze biti do 70 tacaka, tako da sirina terba biti funkcija broj agendi. Npr 25px po tacki agende */
			$("li#gazette_" + id + " > div.info > div.meta > div.box").html("");
			$("li#gazette_" + id + " > div.info > div.meta > div.box").append(div1);
			$("li#gazette_" + id + " > div.info > div.meta > div.box").append(div2);
			
      var chart = new Highcharts.Chart({
      	chart: {
        	renderTo: div2sub,
          type: 'bar'
        },
        title: {
        	text: 'Parlamentarna procedura'
        },
	    	subtitle: {
        	text: 'za usvajanje zakona (broj dana)'
        },
        xAxis: {
	    		categories: gazette_categories,
          title: {
	      	text: 'Verzije zakona',
          	align: 'high'
		  		},
          labels: {
          	rotation: 330
          }
        },
        yAxis: {
        	min: 0,
          title: {
          	text: 'Procedura (broj dana)',
            align: 'high'
          },
          labels: {
          	overflow: 'justify'
          }
        },
        tooltip: {
        	formatter: function() {
          	return this.series.name + ': ' + this.y + ' Dana';
					}
				},
        plotOptions: {
        	bar: {
          	dataLabels: {
            	enabled: false
            }
          }
        },
        credits: {
        	enabled: false
        },
        series: [{
	      	name: 'Predstavnicki Dom',
		  		data: gazette_series_1
        }, {
	      	name: 'Dom Naroda',
		  		data: gazette_series_2
        }]
      });
			
		} else if (tab == 2) { //info
			
			var box_html = '';
			
			if (gazette.length == 0) {
				var box_html = '<div class="a100">No Data</div>';
			} else {
				
				for (i in gazette) {
					box_html += '<a href="javascript:void(0)" onclick="openLaw(' + gazette[i]['gazette_publication_id'] + ');return false;" title="' + gazette[i]['gazette_publication_title'] + '">';
						box_html += '<div class="box a100">';
							box_html += '<span style="width:90px;float:left;">' + gazette[i]['gazette_date'] + ')</span><span style="float:left;width:320px;">' + gazette[i]['gazette_publication_title'].substr(0,130) + (gazette[i]['gazette_publication_title'].length >= 130 ? '...':'') + '</span>';
						box_html += '</div>';
					box_html += '</a>';
				}
			}
			
			$("li#gazette_" + id + " > div.info > div.meta > div.box").html(box_html);
			
		} else if (tab == 3) { //mps in session
			
		} else {}
		
		return false;
	}
</script>


<div class="block_list_wrapper">
	<div class="block_list" style="margin-left:200px;">
  	<ul class="blocks clearfix"><?php
		
    echo '<li class="block first">';
	echo '<div class="info">';
	echo '<h3>Usvojeni<br /><br />Zakoni</h3>';
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
			
	$gpg_type_id=1; // tem fix for laws
			
	if (isset($_GET['search']) && strlen($_GET['search']) > 0) $gazette_publication_groups=get_gazette_publication_groups_by_name($_GET['search']);
	else $gazette_publication_groups=get_gazette_publication_groups($gpg_type_id);

	if (!is_array($gazette_publication_groups) || count($gazette_publication_groups)==0) echo "<h4>No Data</h4>";
	else 
	  {
	    foreach ($gazette_publication_groups as $key=>$gp_group) {
				
	      echo '<li class="block" id="gazette_'.$gp_group['id'].'">';
	      echo '<div class="info">';
						
	      echo '<div class="meta">';
	      echo '<h4>'.substr($gp_group['title'],0,100).(strlen($gp_group['title'])>=100 ? "...":"").'</h4>';
	      //velika kocka
	      echo '<div class="subtitle"><h3>'.$gp_group['title'].'</h3>';
	      // echo '<span class="ui-icon ui-icon-closethick" style="position:absolute;top:5px;right:10px;color:#c60;">close</span>';
	      echo '</div>';

							
	      echo '<div class="box">';
	      echo '</div>';
							
	      echo '<div class="footer">';
	      echo '<a href="javascript:void(0)" onclick="openGazette('.$gp_group['id'].',1);return false;"><span>Pregled</span></a>';
	      echo '<a href="javascript:void(0)" onclick="openGazette('.$gp_group['id'].',2);return false;"><span>Timeline</span></a>';
	      //echo '<a href="javascript:void(0)" onclick="openGazette('.$gp_group['id'].',3);return false;"><span>MPs in Session</span></a>';
	      echo '</div>';
	      //kraj velika kocka
	      echo '</div>';
						
	      echo '<span class="bottom-tag"><span class="perc">'.$gp_group['id'].'</span></span>';
	      //kraj samo za kocku
						
	      echo '</div>'; //info
	      echo '</li>';
				
	    } // end foreach
	  }
			
		?></ul>
  	<div class="clearer"></div>
    <script language="javascript">
			var gazette = [];
			var meta_vote_type = <?php echo json_encode($js_meta_vote_type); ?>;
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