<?php

if (SESSION) {
    if (is_php('5.4.0')) {
        $session = new MongoSession("database/session");
        if (!empty($_COOKIE[COOKIENAME])) {
            $sid = $_COOKIE[COOKIENAME];
        }
        $sid = $session->start($sid);
    }
} else {
	/*
    if (!empty($_COOKIE[COOKIENAME])) {
        $sid = $_COOKIE[COOKIENAME];
        session_id($sid);
        session_start();
    } else {
        session_start();
        $sid = session_id();
        //
    }*/
}
ini_set('session.name', 'NCZSID');
if(!empty($_REQUEST['sid'])){
	//print_r($_GET);die();
	session_id($_REQUEST['sid']);
}
session_start();
//setcookie(COOKIENAME, $sid, time() + 3600*24*32, null, DOMAIN, false, false);

?>