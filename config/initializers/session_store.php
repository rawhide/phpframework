<?php
/*
 * session initialize settings
 */
   
//sesion_name('session_name');
session_name('s');
session_cache_expire(60 * 60 * 24);

//session_regenerate_id();

session_start();
