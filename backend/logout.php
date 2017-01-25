<?php

include 'classes/Authentication.php';

Authentication::destroySession();

header('Location: index.php');
