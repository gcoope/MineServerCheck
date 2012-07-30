<?php
$host = '71.19.149.181'; //Enter server's IP here
$port = '25565'; //Enter server port. Default is 25565
$to=30; //Set timeout
function pingserver($host, $port=25565, $timeout=30) { //These are set as defaults, changes made above will override them
	//Set up socket
	$fp = fsockopen($host, $port, $errno, $errstr, $timeout);
	if (!$fp) return false;
	
	//Send 0xFE: Server list ping
	fwrite($fp, "\xFE");
	
	//Read as much data as we can (max packet size: 241 bytes)
	$d = fread($fp, 256);
	
	//Check we've got a 0xFF Disconnect
	if ($d[0] != "\xFF") return false;
	
	//Remove the packet ident (0xFF) and the short containing the length of the string
	$d = substr($d, 3);
	
	//Decode UCS-2 string
	$d = mb_convert_encoding($d, 'auto', 'UCS-2');
	
	//Split into array
	$d = explode("\xA7", $d);
	
	//Return an associative array of values
	return array(
		'motd'        =>        $d[0],
		'players'     => intval($d[1]),
		'max_players' => intval($d[2]));
}
$serverinfo = pingserver($host, $port, $to);

if($serverinfo)
{
	$online = "<my style=\"color:green\">Online</my>";
}
else
{
	$online = "<my style=\"color:red\">Offline</my>";
}

$status = $online;
if($serverinfo['motd'])
{
	$message = $serverinfo['motd'];
}else{$message="N/A";}

if($serverinfo['max_players'])
{
	$maxplayers = $serverinfo['max_players'];
}else{$players="N/A";}
$players = $serverinfo['players'];
?>

<b>Server status: </b><?php echo $status;?><br />
<b>Server Message: </b><?php echo $message; ?><br />
<b>Online players: </b><?php echo $players; ?>/<?php echo $maxplayers; ?>