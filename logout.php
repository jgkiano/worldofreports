<?php

include 'classes/Authentication.php';

$auth = new Authentication();

Authentication::destroySession();

header(BASE_URL_REDIRECT);
