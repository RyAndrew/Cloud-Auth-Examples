<?php

//show all errors
ini_set('display_errors',1);
error_reporting(E_ALL);

//hide all errors
//ini_set('display_errors',0);
//error_reporting(0);

//no caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//sessions
session_name('auth');
session_start();
