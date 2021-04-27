<?php

require($_SERVER['DOCUMENT_ROOT'] . '/init.php');

require($_SERVER['DOCUMENT_ROOT'] . '/clearsession.php');

header('Location: /', true, 302);

echo "Logged out!";