<?php

include 'classes/Authentication.php';

Authentication::destroySession();

header(BASE_URL_REDIRECT);
