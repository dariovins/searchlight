<?php
function login_form($valid = 0,$mail = true) {
	
  if (!isset($_POST['username'])) $_POST['username'] = "";
	
  $submit_link = $_SERVER['PHP_SELF'];

  echo '<form action="'.$submit_link.'" method="post" align="center">
<br />
User Name:
<br />
<input id="username" maxlength="100" name="username" value="'.$_POST['username'].'" type="text">
<br />
<br />
Password:
<br />
<input id="userpass" maxlength="100" name="userpass" value="" type="password"> 
<br />
<br />
<input class="button" id="submit" name="submit" value="Login" type="submit">
</form>
';


} // end function
