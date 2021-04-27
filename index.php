<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

?>
<html>
<head>
<title>&#x1F913;</title>
<style>
html,body {
	font-family:arial;
}
</style>
</head>
<body>
<h2>&#x1F913; &#x1F9D0;</h2>
<BR>
<?php

if(!isset($_SESSION['user'])) {
	echo "<a href=\"/login/?type=o365\">Login via Office 365</a><BR>";
	echo "<a href=\"/login/?type=okta\">Login via Okta</a><BR>";
	echo "<a href=\"/login/?type=github\">Login via GitHub</a><BR>";
}else{
	echo "Welcome Back {$_SESSION['user']} <a href=\"?jwt\">jwt</a><BR>\r\n";
	echo "<BR>\r\n";
	echo "You are logged in via {$_SESSION['via']}<BR>\r\n";
	echo "<a href=\"/logout/\">Logout</a><BR>\r\n";
	if(isset($_GET['jwt'])){
		echo "<PRE>{$_SESSION['jwt']}</PRE>";
	}
}

echo "<BR>\r\n<BR>\r\n";
echo date('Y-m-d g:ia') . " from {$_SERVER['REMOTE_ADDR']}";