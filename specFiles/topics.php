<?php
if (isset($_GET['theme_policy_area_group_id']) && $_GET['theme_policy_area_group_id'] > 0) {
  $theme_policy_area = get_theme_policy_area($_GET['theme_policy_area_group_id']);

  //  $theme_policy_area = get_gui_table("theme","theme_policy_area_group_id",$_GET['theme_policy_area_group_id']);
	
	if($theme_policy_area===false) {
		echo '[]';
	} else { 
		
		echo json_encode($theme_policy_area);
		
		unset($theme_policy_area);
	}
} else if (isset($_GET['cat']) && strlen($_GET['cat']) > 0) {
  include "specFiles/session.php";
} else {
?>
  <?php  include "specFiles/session.php"; ?>
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
			
			var theme_policy_area_group_id = $(this).attr("id").substr(24);
			
			openGazette(theme_policy_area_group_id,1);
		});
		
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
 



	function openGazette(id,tab) {
		
		$("li#theme_policy_area_group_" + id + " > div.info > div.meta > div.footer > a > span.active").removeClass('active');
		$("li#theme_policy_area_group_" + id + " > div.info > div.meta > div.footer > a:eq(" + (tab-1) + ") > span").addClass('active');
		
		if (tab == 1) { //summary
			
			$.ajax({
				type: "GET",
				dataType: "json", 
				url: "members.php?sel=topics&theme_policy_area_group_id=" + id,
				scriptCharset: "utf-8",
				beforeSend: function(XMLHttpRequest){
					$("li#theme_policy_area_group_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Loading data, please wait ...</div>');
				},
				success: function(data){
					
					var box_html = '';
					theme_policy_area = [];
					
					if (data.length == 0) {
						var box_html = '<div class="a100">No Data</div>';
					} else {
						
						for (i=0; i<data.length; i++) {
							theme_policy_area[data[i]['id']] = data[i];
							
							box_html += '<a href="javascript:void(0)" onclick="openTheme(' + data[i]['id'] + ');return false;" title="' + data[i]['title'] + '">';
								box_html += '<div class="box a100">';
									box_html += '<span style="width:90px;float:left;">' + data[i]['id'] + ')</span><span style="float:left;width:320px;">' + data[i]['title'] + '</span>';
								box_html += '</div>';
							box_html += '</a>';
						}
					}
					
					$("li#theme_policy_area_group_" + id + " > div.info > div.meta > div.box").html(box_html);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("li#theme_policy_area_group_" + id + " > div.info > div.meta > div.box").html('<div class="a100">Error reading data</div>');
				}
			});
		}
		
		return false;
	}
</script>
<div class="block_list_wrapper">
	<div class="block_list">
  	<ul class="blocks clearfix"><?php
		
			echo '<li class="block first">';
				echo '<div class="info">';
					echo '<h3>European Union<br /><br />Policy Areas</h3>';
				echo '</div>';
			echo '</li>';
			
			$theme_policy_area_groups=get_theme_policy_area_group();
			
			foreach ($theme_policy_area_groups as $key=>$tap_group) {
				
				echo '<li class="block" id="theme_policy_area_group_'.$tap_group['id'].'">';
					echo '<div class="info">';
						
						echo '<div class="meta">';
							echo '<h4>'.$tap_group['title'].'</h4>';
							//velika kocka
							echo '<div class="subtitle"><h3>'.$tap_group['title'].'</h3></div>';
							
							echo '<div class="box">';
							echo '</div>';
							
							echo '<div class="footer">';
								echo '<a href="javascript:void(0)" onclick="openGazette('.$tap_group['id'].',1);return false;"><span>Policy Areas</span></a>';
							echo '</div>';
							//kraj velika kocka
						echo '</div>';
						
						echo '<span class="bottom-tag"><span class="perc">'.$tap_group['id'].'</span></span>';
						//kraj samo za kocku
						
					echo '</div>'; //info
				echo '</li>';
				
			} // end while
			
		?></ul>
  	<div class="clearer"></div>
    <script language="javascript">
			var theme_policy_area= [];
		</script>
	</div>
</div>
<!-- END INNER -->
		    <?php } ?>