<?php

require($_SERVER['DOCUMENT_ROOT'] . '/authlib/vendor/autoload.php');

use Jumbojett\OpenIDConnectClient;

if(!isset($_GET['type'])){
	if(!isset($_SESSION['viaType'])){
		die('Invalid auth type');
	}else{
		$_GET['type'] = $_SESSION['viaType'];
	}
}

require($_SERVER['DOCUMENT_ROOT'] . '/secrets.php');

switch($_GET['type']){
	case 'o365':
		$_SESSION['viaType'] = 'o365';
		$_SESSION['via'] = 'Office 365';

		$provider = "https://login.microsoftonline.com/{$office365_tenant}/v2.0/";
		$appId = $office365_appId;
		$cert = $office365_cert;
		$oidc = new OpenIDConnectClient($provider,$appId);
		$oidc->setCertPath($cert);
		$oidc->setResponseTypes(['id_token']);
		$oidc->addScope(['profile']);
		break;
	case 'okta':
		$_SESSION['viaType'] = 'okta';
		$_SESSION['via'] = 'Okta';

		$provider = $okta_provider;
		$appId = $okta_appId;
		$appSecret = $okta_secret;
		$oidc = new OpenIDConnectClient($provider,$appId, $appSecret);
		$oidc->addScope(['profile']);
		break;
	case 'github':
		$_SESSION['viaType'] = 'github';
		$_SESSION['via'] = 'GitHub';

		$provider = 'https://github.com/login/';
		$appId = $github_appId;
		$appSecret = $github_secret;
		$oidc = new OpenIDConnectClient($provider,$appId, $appSecret);
		$oidc->providerConfigParam([
			'userinfo_endpoint'=>'https://api.github.com/user',
			'token_endpoint'=>'https://github.com/login/oauth/access_token',
			'authorization_endpoint'=>'https://github.com/login/oauth/authorize',
			'token_endpoint_auth_methods_supported' => ['client_secret_basic']
		]);
		$oidc->addScope(['user:email']);

		break;
	default:
		die('Invalid auth type');
}

$oidc->setAllowImplicitFlow(true);
$oidc->addAuthParam(['response_mode' => 'form_post']);
$oidc->setRedirectURL($redirectUrl);


try{
	$oidc->authenticate();
}catch(Exception $error){
	require($_SERVER['DOCUMENT_ROOT'] . '/clearsession.php');
	echo "Authentication Error!<BR>\r\n";
	echo $error->getMessage();
	//header('Location: /', true, 302);
	exit;
}

$verifiedClaims = $oidc->getVerifiedClaims();
$errors = [];
// if(!isset($verifiedClaims->email)){
// 	$errors[] = 'Missing Email Claim';
// }
if(!isset($verifiedClaims->name)){
	$errors[] = 'Missing Name Claim';
}
if(count($errors) > 0){
	echo "Authentication Error!<BR>\r\nApp Permissions problem!<BR>\r\n";
	echo implode('<BR>\r\n',$errors);
	exit;
}

$_SESSION['jwt'] = json_encode($verifiedClaims, JSON_PRETTY_PRINT);
$_SESSION['user'] = $verifiedClaims->name;
// $_SESSION['email'] = $verifiedClaims->email;

header('Location: /', true, 302);

echo "authenticated!";