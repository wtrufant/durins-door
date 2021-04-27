<?php

// 'PW' => 'User'. I'm guessing that there won't be a lot of users, so manually updating in two places should be easy.
// If not using mikrotik, the same username needs to be in actions.sh

$pws = array(
    'ValheimPW' => array('user' => 'VAL', 'expire' => '3 hours'),
    'A different PW' => array('user' => 'CPC', 'expire' => '1 week'),
);

// Firewall configs.
$FW = ''; // current options: iptables, mikrotik

$mikrotik = array(
    'addr' => '192.168.1.1', // Router IP.
    'protocol' => 'http', // http or https.
    'user' => 'admin', // User with xxx perms.
    'pw' => 'adminPW' // User's PW.
);
