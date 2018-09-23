<?php
	
	if (isset($_GET['list']) && $_GET['list'] == "agenda") {
		
		if (isset($_GET['session_id']) && $_GET['session_id'] > 0) {
			$agenda = get_gui_table('agenda','session_id',$_GET['session_id']);
		} else if (isset($_GET['agenda_gazette_publication_id']) && $_GET['agenda_gazette_publication_id'] > 0) {
			$agenda = get_gui_table('agenda','agenda_gazette_publication_id',$_GET['agenda_gazette_publication_id']);
		}
		
		if($agenda===false) {
			echo '[]';
		} else { 
			$js_agenda = array();
			foreach ($agenda as $key=>$val) {
				$agenda[$key]['title']=get_info_agenda($val['agenda_id'],'title');
				
				if (isset($_GET['agenda_gazette_publication_id']) && $_GET['agenda_gazette_publication_id'] > 0) {
					$agenda[$key]['session_title']=get_info_session($val['session_id'],'title');
				}
				
				$js_agenda[$agenda[$key]['agenda_id']] = $agenda[$key];
			}
			
			unset($val);
			unset($key);
			
			echo json_encode($js_agenda);
			
			unset($agenda);
			unset($js_agenda);
		}
		
	} else if (isset($_GET['list']) && $_GET['list'] == "gazette") {
		
		if (isset($_GET['gazette_publication_group_id']) && $_GET['gazette_publication_group_id'] > 0) {
			$gazette = get_gui_table("gazette","gazette_publication_group_id",$_GET['gazette_publication_group_id']);
		}
		
		if($gazette===false) {
			echo '[]';
		} else { 
			$js_gazette = array();
			foreach ($gazette as $key=>$val) {
				$js_gazette[$gazette[$key]['gazette_publication_id']] = $gazette[$key];
			}
			
			unset($val);
			unset($key);
			
			echo json_encode($js_gazette);
			
			unset($gazette);
			unset($js_gazette);
		}
	}
	
?>