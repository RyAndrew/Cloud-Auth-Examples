<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

if(isset($_SESSION['user'])) {
	//already logged in!
	header('Location: /', true, 302);
}else{
	require('../oidc_login.php');
}