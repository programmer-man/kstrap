<?php
	
add_action('init', 'websiteStartSession', 1);
add_action('wp_logout', 'websiteEndSession');
add_action('wp_login', 'websiteEndSession');

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

function websiteStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function websiteEndSession() {
    session_destroy ();
}

//echo 'pow!';

?>