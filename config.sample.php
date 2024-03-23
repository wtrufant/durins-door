<?php

// You may need to set your local timezone if unset:
//date_default_timezone_set('America/Chicago');

// 'PW' => 'User'. I'm guessing that there won't be a lot of users, so manually updating in two places should be easy.

$pws = array(
	"Odin's Beard" => array('user' => 'VAL', 'expire' => '3 hours'),
	'Cr33p3r!' => array('user' => 'MINE', 'expire' => '3 hours'),
	"It's-a me!" => array('user' => 'ME', 'expire' => '1 week'),
);

$theme = 'lock'; // Current options: lock

// Firewall config.
$FW = 'mikrotik'; // current options: firewalld, iptables, mikrotik, ufw

$mikrotik = array(
	'proto' => 'http', // http or https.
	'addr' => '192.168.1.1', // Router IP.
	'port' => '80', // 443 for default HTTPS, or something custom?
	'user' => 'admin', // User with xxx perms.
	'pw' => 'password' // User's PW.
);
