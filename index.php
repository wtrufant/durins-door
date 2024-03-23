<?php
require './config.php';
$bg = "PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZD0iTTI0IDRjLTcgMC0xMiA0LjItMTIgMTF2NmgtMmMtMiAwLTMgMS0zIDN2MTdjMCAxIDEgMyAzIDNoMjkuNGMxLjYgMCAyLjYtMiAyLjYtM1YyNGMwLTItMS0zLTMtM2gtMnYtNmMwLTctNS0xMS0xMi0xMWgtMXptMCA1aDFjNyAwIDcgNiA3IDh2NEgxN3YtNGMwLTIgMC04IDctOHoiIGZpbGw9IiM2YjZiNmIiLz48L3N2Zz4="; //"locked" image
if(isset($_POST['p'])) {
	if( array_key_exists($_POST['p'], $pws) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ) {
		$pw = $_POST['p'];
		switch($FW) {
			case 'firewalld':
				// Placeholder for firewalld
				break;

			case 'iptables':
				// Placeholder for local iptables
				break;

			case 'mikrotik':
				// https://help.mikrotik.com/docs/display/ROS/REST+API
				$urlBase = $mikrotik['proto'].'://'.$mikrotik['addr'].':'.$mikrotik['port'];
				
				// The unique ID we'll use to label the IP and then clean it later.
				$id = "tmp".$pws[$pw]['user'].date('YmdHis');

				// Init Curl Handler, set common options:
				$ch = curl_init();
				$headers = array( 'Content-Type: application/json', 'Authorization: Basic '.base64_encode($mikrotik['user'].":".$mikrotik['pw']));
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

				// Now let's add our IP to the address-list
				curl_setopt($ch, CURLOPT_URL, $urlBase.'/rest/ip/firewall/address-list');
				$address_lists = array("address" => $_SERVER['REMOTE_ADDR'], "comment" => $id, "disabled" => "false", "dynamic" => "false", "list" => "allowWT3" );
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($address_lists));
				$head = curl_exec($ch);

				// Now we add to scheduler
				curl_setopt($ch, CURLOPT_URL, $mikrotik['proto'].'://'.$mikrotik['addr'].':'.$mikrotik['port'].'/rest/system/scheduler');
				$scheduler = array(
					"name" => $id,
					"comment" => "clear temp allow rule and this to-do in ".$pws[$pw]['expire'].".",
					"start-date" => date("M/d/Y", strtotime('+'.$pws[$pw]['expire'])),
					"start-time" => date("H:i:s", strtotime('+' . $pws[$pw]['expire'])),
					"interval" => "00:00:00",
					"on-event" => "/ip firewall address-list remove [find where comment~\"^".$id."\"]; /system scheduler remove ".$id,
					"policy" => "read,write"
				);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($scheduler));
				$head = curl_exec($ch);

				//$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				/* curl API examples:
				curl -u 'USER:PASS' -X GET   -H "content-type: application/json" http://10.0.0.1/rest/ip/firewall/address-list
				curl -u 'USER:PASS' -X PATCH -H "content-type: application/json" http://10.0.0.1/rest/ip/firewall/address-list/*5 --data '{"disabled":"true"}'
				curl -u 'USER:PASS' -X POST  -H "content-type: application/json" http://10.0.0.1/rest/ip/firewall/address-list --data '{"address":"1.2.3.4","comment":"API Test","disabled":"false","dynamic":"false","list":"allowWT3"}'
				*/
				break;

			case 'ufw':
				//placeholder
				break;
		}

	$bg = "PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZD0iTTM5IDIxaC0ydi02YzAtNy01LTExLTEyLTExaC0xYy02LjYgMC0xMS41IDMuOC0xMiAxMGg1LjJjLjUtMi40IDIuMS01IDYuNy01aDFjNyAwIDcgNiA3IDh2NEgxMGMtMiAwLTMgMS0zIDN2MTdjMCAxIDEgMyAzIDNoMjkuNGMxLjYgMCAyLjYtMiAyLjYtM1YyNGMwLTItMS0zLTMtM3oiIGZpbGw9IiM2YjZiNmIiLz48L3N2Zz4="; //"unlocked" image
	}
}

?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
	<title>Access</title>
	<meta name="Description" content="Access">
	<meta name="apple-mobile-web-app-title" content="Access">
	<meta name="application-name" content="Access">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#6B6B6B">
	<link rel="shortcut icon" type="image/x-icon" href="<?= $_SERVER['REQUEST_URI']; ?>/lock.svg">
	<link rel="icon" type="image/x-icon" href="<?= $_SERVER['REQUEST_URI']; ?>/lock.svg">
<?php /*    <link rel="manifest" href="<?= $_SERVER['REQUEST_URI']; ?>/manifest.json">*/ ?>

	<style>
		body, html { margin: 0; padding: 0; color: #6b6b6b; background-color: #A3A3A3; width: 100%; height: 100%; }
		.lock { position: relative; display: inline-block; margin: 15px; width: calc(100% - 30px); height: calc(100% - 30px); text-align: center; }
		.lock input {
			text-align: center; margin-top: 30px; width: 285px; padding: 150px 0 75px 0; border: none;
			background-repeat: no-repeat; background-size: contain; background-position: center top; background-color: unset;
			background-image: url("data:image/svg+xml;base64,<?= $bg; ?>");
		}
		label { display: none; }
	</style>
</head>
<body>
<form id="auth" name="auth" method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" class="lock" autocomplete="off">
	<?php if(isset($delDate)) { echo $delDate." ".$delTime; } else { ?><label for="p">Password:</label><input type="password" name="p" id="p" autocomplete="new-password" value=""><?php } ?><br>
	<?= $_SERVER['REMOTE_ADDR'].( $_SERVER['HTTP_X_FORWARDED_FOR'] ? '<br>P: '.$_SERVER['HTTP_X_FORWARDED_FOR'] : '' ); ?>
</form>
</body>
</html>
