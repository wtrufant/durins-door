<?php 
$bg = "PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZD0iTTI0IDRjLTcgMC0xMiA0LjItMTIgMTF2NmgtMmMtMiAwLTMgMS0zIDN2MTdjMCAxIDEgMyAzIDNoMjkuNGMxLjYgMCAyLjYtMiAyLjYtM1YyNGMwLTItMS0zLTMtM2gtMnYtNmMwLTctNS0xMS0xMi0xMWgtMXptMCA1aDFjNyAwIDcgNiA3IDh2NEgxN3YtNGMwLTIgMC04IDctOHoiIGZpbGw9IiM2YjZiNmIiLz48L3N2Zz4="; //"locked" image
require './config.php';
if(isset($_POST['p'])) {
    if( array_key_exists($_POST['p'], $pws) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ) {
        switch($FW) {
            case 'iptables':
                file_put_contents('people.txt', $pws[$_POST['p']]." ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND);
                break;

            case 'mikrotik':
                $id = "tmp" . $pws[$pw]['user'] . date('YmdHis');
                $pw = $_POST['p'];

                require('./routeros_api.class.php'); // https://github.com/BenMenking/routeros-api
                $API = new RouterosAPI(); //$API->debug = true;

                if ($API->connect('ROUTER_IP', 'admin', 'ROUTER_PW')) {

                    $API->comm("/ip/firewall/address-list/add", array( //add IP to an allow list.
                        "list" => "allow" . $pws[$_POST['p']],
                        "address" => $_SERVER['REMOTE_ADDR'],
                        "comment" => $id,
                    ));
                    $API->comm("/system/scheduler/add", array( //add a job to remove the IP and the job itself in an hour.
                        "name" => $id, "comment" => "clear temp allow rule and this to-do in " . $pws[$pw]['expire'] . ".",
                        "start-date" => date("M/d/Y", strtotime('+' . $pws[$pw]['expire'])), "start-time" => date("H:i:s", strtotime('+' . $pws[$pw]['expire'])), "interval" => "00:00:00",
                        "on-event" => '/ip firewall address-list remove [find where comment~"^' . $id . '"]; /system scheduler remove ' . $id,
                        "policy" => "read,write",
                    )); //,policy,sensitive

                    $API->disconnect();
                }
                break;
        }

    $bg = "PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZD0iTTM5IDIxaC0ydi02YzAtNy01LTExLTEyLTExaC0xYy02LjYgMC0xMS41IDMuOC0xMiAxMGg1LjJjLjUtMi40IDIuMS01IDYuNy01aDFjNyAwIDcgNiA3IDh2NEgxMGMtMiAwLTMgMS0zIDN2MTdjMCAxIDEgMyAzIDNoMjkuNGMxLjYgMCAyLjYtMiAyLjYtM1YyNGMwLTItMS0zLTMtM3oiIGZpbGw9IiM2YjZiNmIiLz48L3N2Zz4="; //"unlocked" image
	}
}

?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <title>Alohomora</title>
    <meta name="Description" content="Alohomora">
    <meta name="apple-mobile-web-app-title" content="Alohomora">
    <meta name="application-name" content="Alohomora">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="HandheldFriendly" content="True">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#6B6B6B">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $_SERVER['REQUEST_URI']; ?>/lock.svg">
    <link rel="icon" type="image/x-icon" href="<?= $_SERVER['REQUEST_URI']; ?>/lock.svg">
    <!--<link rel="manifest" href="<?= $_SERVER['REQUEST_URI']; ?>/manifest.json">-->

	<style>
		body, html { margin: 0; padding: 0; color: #6b6b6b; background-color: #A3A3A3; width: 100%; height: 100%; }
		.lock { position: relative; display: inline-block; margin: 15px; width: calc(100% - 30px); height: calc(100% - 30px); text-align: center; }
		.lock input {
			text-align: center; margin-top: 30px; width: 285px; padding: 150px 0px 75px 0px; border: none;
			background-repeat: no-repeat; background-size: contain; background-position: center top; background-color: unset;
			background-image: url("data:image/svg+xml;base64,<?= $bg; ?>");
		}
		label { display: none; }
	</style>
</head>
<body>
<form id="alohomora" name="alohomora" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" class="lock" autocomplete="off">
    <label for="p">Password:</label><input type="password" name="p" id="p" autocomplete="new-password" value=""><br>
    <?= $_SERVER['REMOTE_ADDR'].( $_SERVER['HTTP_X_FORWARDED_FOR'] ? '<br>P: '.$_SERVER['HTTP_X_FORWARDED_FOR'] : '' ); ?>
</form>
</body>
</html>
