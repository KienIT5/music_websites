<?php
include "../core/functions.php";
if(!empty($_SESSION['USER']))
{
	unset($_SESSION['USER']);
	session_destroy();
	session_regenerate_id();
}

header("Location: http://localhost:84/music_websites/public/login.php");
exit();
