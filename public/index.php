<?php

filter('login_required');

header("Location: /sessions/login.php");
exit;
