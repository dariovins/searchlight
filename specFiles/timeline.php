<script language="javascript">
	$(document).ready(function(){
		
		$("#timeline").dialog({
    	autoOpen: false,
			width: 974,
			modal: true,
			open: function(){
				$('.ui-widget-overlay').bind('click', function () { $(this).siblings('.ui-dialog').find('.ui-dialog-content').dialog('close'); });
				
      	$("body").css("overflow", "hidden");
    	},
    	close: function(){
  	  	$("body").css("overflow", "auto");
	    }
		});
		
	});
	
	function openTimeline(id) {

	  return;

		var data = mp_in_mandate[id];
		
		$("#timeline").find(".image_mp").css("background-image","url('" + data['img'] + "')");
		
		$("#timeline").find(".img-bar").html(data['first_name'] + ' ' + data['last_name']);
		
		$("#timeline").find(".info.mp > .date_place_birth").html(data['dob_f'] + ' / ' + data['place_of_birth']);
		$("#timeline").find(".info.mp > .sprema_zanimanje").html(data['education_level'] + ' / ' + data['zanimanje']);
		$("#timeline").find(".info.mp > .jezici").html(data['foreign_languages']);
		
		
		$("#timeline").dialog("option","title",data['first_name'] + ' ' + data['last_name']).dialog("open");
	}
</script>
<link href="css/timeline.css" rel="stylesheet" type="text/css">
<div id="timeline" style="display:none;">
  <div class="timeline-block timeline-block-first">
    <div class="image_mp">
      <div class="img-bar"></div>
    </div>
    <div class="info mp">
      <h5>Datum / mjesto rođenja:</h5>
      <h3 class="date_place_birth"></h3>
      <h5>Strucna sprema / Zanimanje:</h5>
      <h3 class="sprema_zanimanje"></h3>
      <h5>Strani jezici:</h5>
      <h3 class="jezici"></h3>
      <h5>Specijalizacije:</h5>
      <h3></h3>
      <h5>Radno Iskustvo:</h5>
      <div class="tag">
        <h3>Poslanička karta</h3>
      </div>
    </div>
  </div>
  <div class="timeline-block">
    <div class="info">
      <div class="box a100">
        <h2>Mandat 2010-2014</h2>
        <h4>Poslanik u Predstaničkom domu Parlamenta BiH</h4>
      </div>
      <div class="box a30">
        <h1>76%</h1>
      </div>
      <div class="box a70">
        <h2>Učešće na sjednicama</h2>
        <h4>Prisustvo na 22 od 33 održane sjednice</h4>
      </div>
      <div class="box a30">
        <h1>63%</h1>
      </div>
      <div class="box a70">
        <h2>Glasanje na sjednicama</h2>
        <h4>(glasao: za, protiv ili suzdržan)</h4>
      </div>
      <div class="tag">
        <button>Više informacija o radu poslanika</button>
      </div>
    </div>
  </div>
  <div class="clearer"></div>
  <div class="timeline-block timeline-block-first">
    <div class="box a100">
      <h2>Mandat 2010-2014</h2>
      <h4>Poslanik u Predstaničkom domu Parlamenta BiH</h4>
    </div>
  </div>
  <div class="timeline-block">
    <div class="info">
      <div class="box a100">
        <h2>Mandat 2010-2014</h2>
        <h4>Poslanik u Predstaničkom domu Parlamenta BiH</h4>
      </div>
      <div class="box a30">
        <h1>76%</h1>
      </div>
      <div class="box a70">
        <h2>Učešće na sjednicama</h2>
        <h4>Prisustvovao na 22 od 33 održane sjednice</h4>
      </div>
      <div class="box a30">
        <h1>63%</h1>
      </div>
      <div class="box a70">
        <h2>Aktivno glasanje na sjednicama</h2>
        <h4>(glasao: za, protiv ili suzdržan)</h4>
      </div>
      <div class="tag">
        <button>Više informacija o radu poslanika</button>
      </div>
    </div>
  </div>
  <div class="clearer"></div>
</div>