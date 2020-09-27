<?php
//For full features see https://videowhisper.com/?p=Requirements or get a turnkey streaming plan from https://webrtchost.com/hosting-plans/ 

$options = array(

	'webrtcVideoBitrate' => 750, // demo mode limited to 750kbps, 480p
);

//installation url & integration calls
const VW_H5V_URL = ''; //leave blank if loading from same folder, ex: https://yoursite.com/html5-videochat/
const VW_H5V_CALL = VW_H5V_URL . 'app-call.php?v=1';

//development & debugging
define('VW_DEVMODE', 1);
define('VW_DEVMODE_COLLABORATION', 0);
define('VW_DEVMODE_CLIENT', 0);

if (VW_DEVMODE)
{
	ini_set('display_errors', 1);
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
}