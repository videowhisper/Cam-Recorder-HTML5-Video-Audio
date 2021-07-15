<?php
//For full features see https://videowhisper.com/?p=Requirements or get a turnkey streaming plan from https://webrtchost.com/hosting-plans/ 

$options = array(

	'webrtcVideoBitrate' => 750, // demo mode limited to 750kbps, 480p
	
	'appSetup' => unserialize('a:1:{s:6:"Config";a:12:{s:8:"darkMode";s:0:"";s:16:"resolutionHeight";s:3:"480";s:7:"bitrate";s:3:"750";s:19:"maxResolutionHeight";s:4:"1080";s:10:"maxBitrate";s:4:"3500";s:15:"recorderMaxTime";s:3:"300";s:12:"recorderMode";s:5:"video";s:19:"recorderModeDisable";s:0:"";s:10:"recordStay";s:0:"";s:14:"recordMultiple";s:0:"";s:12:"recordClosed";s:0:"";s:12:"timeInterval";s:6:"300000";}}'),

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