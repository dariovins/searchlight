<script language="javascript">
	$(document).ready(function(){
		
		var prevSlide = 0;
		var curAnimation = -1;
		var maxSlide = $(".slides > div").length - 1;
		var SlideTimer = null;
		var SlideOverTimer = null
		
		function slideImages(slide,stopSlide) {
			if (stopSlide == null) stopSlide = false;
			
			if (SlideTimer) clearTimeout(SlideTimer);
			if (SlideOverTimer) clearTimeout(SlideOverTimer);
			
			if (slide < 0) slide = maxSlide;
			if (slide > maxSlide) slide = 0;
			
			if (prevSlide == slide) {
				$(".slides > div:eq(" + slide + ")").css({"opacity":1,"z-index":10});
			} else {
				
				if (curAnimation >= 0) $(".slides > div:eq(" + curAnimation + ")").stop();
				
				$(".slides > div:eq(" + prevSlide + ")").css({"opacity":1,"z-index":9});
				$(".slides > div:eq(" + slide + ")").css({"opacity":0,"z-index":10});
				
				curAnimation = slide;
				$(".slides > div:eq(" + slide + ")").animate({
					opacity: 1,
				}, 500, function() {
					$(".slides > div:eq(" + prevSlide + ")").css("opacity",0);
					
					prevSlide = slide;
					curAnimation = -1;
				});
			}
			
			$(".slides-control > span.active").removeClass("active");
			$(".slides-control > span:eq(" + slide + ")").addClass("active");
			
			if (stopSlide == false) {
				SlideTimer = setTimeout(function() {
					slideImages(prevSlide + 1);
				}, 10000);
			}
		}
		
		$(".slides-control > span > div").click(function() {
			var index = $(this).attr("id").substr(14,1);
			$(".slides-control > span.active").removeClass("active");
			$(this).parent("span").addClass("active");
			slideImages(index);
		});
		
		$(".slides > div").hover(
			function () {
				var index = $(this).attr("id").substr(14,1);
				slideImages(index,true);
			},
			function () {
				if (SlideOverTimer) clearTimeout(SlideOverTimer);
				
				var index = $(this).attr("id").substr(14,1);
				SlideOverTimer = setTimeout(function() {
					slideImages(index);
				}, 2000);
				
			}
		).click(function() {
			var index = $(this).attr("id").substr(14,1);
			if (index == 0) {
				location.href = "index.php?sel=sessions";
			} else if (index == 1) {
				location.href = "index.php?sel=mp";
			} else if (index == 2) {
				location.href = "index.php?sel=laws";
			} else if (index == 3) {
			  //				location.href = "index.php?sel=topics";
			}
		});
		
		slideImages(0);
	});
</script>
<?php
function savemyinfo()
{
  $query = "INSERT INTO nagradna_igra (vrijeme,ime,tel,email,q1,q2,q3,nagrada) VALUES ('".$_GET['igra']."', '".$_POST['ime']."', '".$_POST['tel']."', '".$_POST['email']."', '".$_POST['q1']."', '".$_POST['q2']."', '".$_POST['q3']."', '".$_POST['nagrada']."')";
  //  $result = mysql_query($query) or die(mysql_error());


    /*
DROP TABLE IF EXISTS nagradna_igra;
CREATE TABLE `nagradna_igra` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `vrijeme` varchar(255) DEFAULT NULL,
 `ime` varchar(255) DEFAULT NULL,
 `tel` varchar(255) DEFAULT NULL,
 `email` varchar(500) DEFAULT NULL,
 `q1` int DEFAULT NULL,
 `q2` int DEFAULT NULL,
 `q3` int DEFAULT NULL,
 `mobitel` varchar (255) DEFAULT NULL,
 `date_lastChange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */
}

?>

<!--
<div style="width:1050px;height:500px;background:rgba(79, 43, 4, 0.92);position:absolute;margin:-13px 90px;z-index:19;border-radius: 6px;-webkit-border-radius: 6px;-moz-border-radius: 6px; 
<?php 

if ($_GET['nopart']==1)
{
       $_SESSION['letmein']=1;
}

   if(
      isset($_SESSION['letmein'])
      ||
      !empty($_POST['q1'])
      &&
      !empty($_POST['q2'])
      &&
      !empty($_POST['q3'])
      &&
      !(empty($_POST['email']) || !(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)))
      &&
      !empty($_POST['ime'])
      &&
      !empty($_POST['tel'])
      &&
      !empty($_POST['nagrada'])
      ) 
     {
       if ($_POST['submit']) {
$mymsg="Hvala na učešću. Sada možete pristupiti Searchlight portalu i saznati sve o radu Parlamenta BiH.";
       savemyinfo();
       $_POST=array();
       $_SESSION['letmein']=1;
}
       echo "display:none";
     }
 ?>
">

   <form action="index.php?igra=<?php echo date("d-M-Y-H:m:s"); ?>" method="post">
   <?php if (is_array($_POST) && isset($_POST['submit'])) $myvar='background:#96140d;' ?>
   <div class="tag-line" style="position:absolute;width:350px;right:27px;bottom:3px;z-index:21;">
   <a href="index.php"><img src="css/images/sl_logo.png" style="border:0;margin-top:20px;height:70px;opacity:0.5" \=""></a>
   </div>
   <div class="slide" style="width:1050px;height:500px;position:absolute;background:none;opacity: 1;"> 
   <div style="position:absolute;left:600px;top:36px;">
   <h1>ŽELIM OSVOJITI:</h1>
   <p>
   <select name="nagrada" style="width:150px;font-size:16px;<?php  if(empty($_POST['nagrada'])) echo $myvar; ?>"><option></option><option <?php if ($_POST['nagrada']=='Samsung Galaxy mini 2 S6500') echo 'selected'; ?> >Mobitel: Samsung Galaxy mini 2 S6500</option><option <?php if ($_POST['nagrada']=='Tablet Yarvik Luna 10') echo 'selected'; ?> >Tablet: Yarvik Luna 10"</option><option <?php if ($_POST['nagrada']=='Samsung ST 200') echo 'selected'; ?> >Foto aparat: Samsung ST 200</option></select>
   </p>
   </div>
   <div style="position:absolute;left:940px;top:36px;"><img src="css/images/samsunggalaxymini2.png" height="100px;" /></div>
   <div style="position:absolute;left:900px;top:156px;"><img src="css/images/samsungst200.png" height="70px;" /></div>
   <div style="position:absolute;left:900px;top:256px;"><img src="css/images/tabletyarkivluna10.png" height="80px;" /></div>

	<div style="position:absolute;left:10px;bottom:0;">
	  <p style="font-size:10px;z-index:999;"><a target="_BLANK" href="searchlight pravila.pdf">Pravila nagradne igre</a></p>
	</div>
	<div style="position:absolute;left:30px;bottom:60px;">
	  <p style="font-size:14px;z-index:999;"><a href="#" style="color:#fff;">Sva polja su obavezna!</a></p>
	</div>
	<div style="position:absolute;left:260px;bottom:0;">
	  <p style="font-size:14px;z-index:999;"><a href="index.php?nopart=1" style="color:#fff;">Ne želim sudjelovati. Pusti me u Searchlight!</a></p>
	</div>

	<div class="fl" style="margin-left:30px;width:520px;float:left;">
	  <h2>NAGRADNA IGRA</h2>
	  <p>Searchlight!</p>
	  <div>
            <h4 style="font-size:18px;">ODGOVORI NA PITANJA:</h4>
            <h3>&nbsp;</h3>
            <h3>Koliko domova ima Parlamentarna Skupština BiH (PS BiH)?</h3>
            <h3>&nbsp;</h3>
	    <select name="q1" style="width:150px;font-size:16px;<?php if(empty($_POST['q1'])) echo $myvar; ?>"><option></option><option <?php if ($_POST['q1']==1) echo 'selected'; ?> >1</option><option <?php if ($_POST['q1']==2) echo 'selected'; ?> >2</option><option <?php if ($_POST['q1']==3) echo 'selected'; ?> >3</option></select>
            <h3>&nbsp;</h3>
            <h3>Koliko poslanika ima u Predstavničkom Domu PS BiH?</h3>
            <h3>&nbsp;</h3>
	    <select name="q2" style="width:150px;font-size:16px;<?php  if(empty($_POST['q2'])) echo $myvar; ?>"><option></option><option <?php if ($_POST['q2']==5) echo 'selected'; ?> >5</option><option <?php if ($_POST['q2']==42) echo 'selected'; ?> >42</option><option <?php if ($_POST['q2']==123) echo 'selected'; ?> >123</option></select>
            <h3>&nbsp;</h3>
            <h3>Do kada traje tekući mandat Doma Naroda PS BiH?</h3>
            <h3>&nbsp;</h3>
	    <select name="q3" style="width:150px;font-size:16px;<?php  if(empty($_POST['q3'])) echo $myvar; ?>"><option></option><option <?php if ($_POST['q3']==2014) echo 'selected'; ?> >2014</option><option <?php if ($_POST['q3']==2015) echo 'selected'; ?> >2015</option><option <?php if ($_POST['q3']==2016) echo 'selected'; ?> >2016</option></select>
	  </div>
	</div>
	<div class="fl" style="width:420px;float:left;margin-left:50px;">
	  <h2>&nbsp;</h2>
	  <p>&nbsp;</p>
	  <div>
            <h4 style="font-size:18px;">ISPUNI INFORMACIJE O SEBI:</h4>
            <h3>&nbsp;</h3>
            <h3>Ime i prezime:</h3>
            <h3>&nbsp;</h3>
            <input name="ime" value="<?php echo $_POST['ime'];?>" type="text" style="width:250px;height:25px;padding:0 6px;<?php if(empty($_POST['ime'])) echo $myvar; ?>" autocomplete="off" />
            <h3>&nbsp;</h3>
            <h3>E-mail adresa:</h3>
            <h3>&nbsp;</h3>
            <input name="email" type="text" value="<?php echo $_POST['email'];?>" style="width:250px;height:25px;padding:0 6px;<?php if(empty($_POST['email']) || !(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) echo $myvar; ?>" autocomplete="off" />
            <h3>&nbsp;</h3>
            <h3>Broj telefona:</h3>
            <h3>&nbsp;</h3>
            <input name="tel" type="text"  value="<?php echo $_POST['tel'];?>" style="width:250px;height:25px;padding:0 6px;<?php if(empty($_POST['tel'])) echo $myvar; ?>" autocomplete="off" />
	  </div>

<input type="submit" class="more" style="position:absolute;left:200px;bottom:30px;width:430px;padding:8px 10px;border-radius: 12px;-webkit-border-radius: 12px;-moz-border-radius: 12px;z-index:300;border:1px solid #c60;background:#c60;font-size:18px;color: #EFAE3E;text-transform: uppercase;font-size: 14px;line-height: 110%;font-weight: bold;margin-bottom: 3px;text-align:center;" name="submit" value="pošalji i pristupi Searchlight portalu!">
	</div>
      </div>
</form>
   </div>
-->

<div class="intro-slideshow-wrapper">
  <div class="intro-slideshow">
    <div class="slides">
    	<?php
			  $query = "SELECT * FROM gui_home ORDER BY data_date DESC LIMIT 1";
				$result = mysql_query($query) or die(mysql_error());
				$data = mysql_fetch_assoc($result);
			?>
      <div id="slide-control_0" class="slide slide1" style="opacity: 1; z-index: 10;"> <span class="large-image magnifier-large fl"></span>
        <div class="fl">
          <h2><?php echo get_gui_label(1,'Parlamentarna Skupština BiH'); ?></h2>
          <p><?php echo get_gui_label(-1,'Sve o aktivnostima, sjednicama, debatama, odlukama'); ?></p>
          <div>
            <h3>Mandat 2010-2014</h3>
            <h4><?php $d=intval(abs(strtotime(gmDate("Y-m-d"))-strtotime(get_info_mandate(2, 'date_start')))/86400); echo $d; ?></h4>
            <h3><?php echo get_gui_label(11,'Dan saziva'); ?></h3>
          </div>
          <div>
            <h3>Predstavnicki Dom</h3>
            <h4><?php $d=count(get_sessions(2)); echo $d; unset($d); ?></h4>
            <h3>Sjednica</h3>
          </div>
          <div>
            <h3>Dom Naroda</h3>
            <h4><?php $d=count(get_sessions(4)); echo $d; unset($d); ?></h4>
<!--            <h4><?php echo $data['hop_sessions']; ?></h4> -->
            <h3>Sjednica</h3>
          </div>
        </div>
        <div class="more"><h3>Saznaj više ...</h3></div>
      </div>
      
      <div id="slide-control_1" class="slide slide2" style="opacity: 0; z-index: 9;"> <span class="lightbulb-large fl"></span>
        <div class="fl">
          <h2><?php echo get_gui_label(2,'Poslanici i Delegati'); ?></h2>
          <p> Sve o njihovom radu, aktivnostima, prisustvu, glasanju </p>
          <div>
            <h3>Predstavnici</h3>
            <h4><?php echo $data['hor_mps']; ?></h4>
            <div class="inner">
              <div class="gender"><?php echo get_gui_label(5,'M'); ?></div>
              <div class="broj"><?php echo $data['hor_male']; ?></div>
              <div class="perc">%</div>
            </div>
            <div class="inner2">
              <div class="gender"><?php echo get_gui_label(6,'Ž'); ?></div>
              <div class="broj"><?php echo $data['hor_female']; ?></div>
              <div class="perc">%</div>
            </div>
          </div>
          <div>
            <h3>Prisustvo</h3>
            <h4><?php echo $data['hor_attendance']; ?>%</h4>
          </div>
          <p class="inner"> Predstavnicki Dom </p>
          <div>
            <h3>Poslanici</h3>
            <h4><?php echo $data['hop_mps']; ?></h4>
            <div class="inner">
              <div class="gender"><?php echo get_gui_label(5,'M'); ?></div>
              <div class="broj"><?php echo $data['hop_male']; ?></div>
              <div class="perc">%</div>
            </div>
            <div class="inner2">
              <div class="gender"><?php echo get_gui_label(6,'Ž'); ?></div>
              <div class="broj"><?php echo $data['hop_female']; ?></div>
              <div class="perc">%</div>
            </div>
          </div>
          <div>
            <h3>Prisustvo</h3>
            <h4><?php echo $data['hop_attendance']; ?>%</h4>
          </div>
          <p class="inner"> Dom Naroda </p>
        </div>
        <div class="more"><h3>Saznaj više ...</h3></div>
      </div>
      
      <div id="slide-control_2" class="slide slide3" style="opacity: 0; z-index: 9;"> <span class="topic-large fl"></span>
        <div class="fl">
          <h2>Usvojeni zakoni </h2>
          <p> Saznajte koji su zakoni usvojeni u ovom sazivu </p>
          <div>
            <h3>Usvojen</h3>
            <h4><?php echo $data['laws_adopted']; ?></h4>
            <h3>Zakon</h3>
          </div>
          <div>
            <h3>Procedura traje</h3>
            <h4><?php echo $data['laws_days']; ?></h4>
            <h3>dana u prosjeku</h3>
          </div>
        </div>
        <div class="more"><h3>Saznaj više ...</h3></div>
      </div>
      
      <div id="slide-control_3" class="slide slide4" style="opacity: 0; z-index: 9;"> <span class="unlock-large fl"></span>
        <div class="fl">
          <h2>Pretraži Searchlight</h2>
          <p> Sjednice, poslanici i delegati, debate, glasanje, zakoni </p>
          <div>
            <h3>Preko</h3>
            <h4><?php echo number_format($data['data_count'],0,".",","); ?></h4>
            <h3>Podataka</h3>
          </div>
          <div>
            <h3>&nbsp</h3>
      <?php if ($_GET['sel'] == 'search') { ?>
      <div class="ui-corner-all"  style="position:absolute;top:6px;height:27px;left:223px;padding:5px;background:#efae3e;">
	<h4>Prikazujem rezultate za: "<?php echo $_POST['search']; ?>"</h4>
      </div>
      <?php } ?>

	<form id="searchform">
            <h4 style="padding-top: 5px;padding-bottom: 10px;">
              <input id="search" type="text" style="width:250px;height:30px;" autocomplete="off" />
              <div id="search_type" class="ui-corner-all" style="">
				  <label><input name="search_type" type="radio" value="search_list" checked /> Sjednice, dnevni red, debate</label>
                <label><input name="search_type" type="radio" value="search_laws" /> Zakoni</label>
                <label><input name="search_type" type="radio" value="search_mp" /> Poslanici i delegati</label>
              </div>
            </h4>
	  <input type="submit" style="margin-left:60px;text-rendering: optimizeLegibility;background:transparent;border:0;cursor:pointer;color:#c60;text-transform:uppercase;font-weight:bold;font-size:14px;" value="Pretraži Searchlight" />
	 </form>
          </div>
        </div>
      </div>
      
    </div>
    
    <div class="slides-control">
    	<span><div class="magnifier-large" id="slide-control_0" style="opacity: 0.3; ">;</div></span>
      <span><div class="lightbulb-large" id="slide-control_1" style="opacity: 0.3; ">;</div></span>
      <span><div class="topic-large" id="slide-control_2" style="opacity: 0.3; ">;</div></span>
      <span><div class="unlock-large" id="slide-control_3" style="opacity: 0.3; ">;</div></span>
    </div>
  </div>
  <!-- end slideshow -->
  
  <div class="tag-line" style="padding-top:10px;z-index:21;">
<a href="index.php"><img src="css/images/sl_logo.png" style="border:0;margin-top:20px;height:70px;opacity:0.5" \=""></a>
  </div>
</div>
<!-- end wrapper --> 

<?php if (isset($mymsg)) echo '<div style="position:absolute;top:20px;left:20px;"><h4>'.$mymsg.'</h4></div>' ;?></div>
