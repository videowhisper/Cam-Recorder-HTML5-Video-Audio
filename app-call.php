<?php
//app-call.php : called by app to communicate with web server (login, config, status, interactions)
//This is sample code for demostrating some features. Not designed for production.
//Integrate with own platform login and database system.
//For a functional turnkey integration see PaidVideochat.com turnkey site solution and plugin source code https://plugins.svn.wordpress.org/ppv-live-webcams/trunk/inc/h5videochat.php .

include_once 'settings.php';

include_once 'app-functions.php';

require_once __DIR__ . '/demo-security.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') demo_json(['error'=>'POST required.'], 405);
$VideoWhisper = $_POST['VideoWhisper'] ?? null;
if (!is_array($VideoWhisper)) demo_json(['error'=>'Session required.'], 403);
$proof = demo_validate_request($VideoWhisper);
$userID = $proof['user']; $sessionID = $proof['session'];
$roomID = (int)$VideoWhisper['roomID']; $sessionKey = $proof['key'];
$isPerformer = $proof['owner']; $privateUID = 0; $roomActionID = 0;
$response = ['VideoWhisper'=>'https://videowhisper.com'];
$task = is_string($_POST['task'] ?? null) ? $_POST['task'] : '';
$userName = (($userID>10000)?'Performer':'User') . $userID;
if ($task === 'socket_auth') demo_json(['vwsAuth'=>demo_pin($userName, $options)]);
// Serialize each room's read/modify/write chat updates across browser sessions.
$roomLock = fopen(demo_state_dir() . '/' . $roomID . '.lock', 'c');
if (!$roomLock || !flock($roomLock, LOCK_EX)) demo_json(['error'=>'Room unavailable.'], 503);

if ($task == 'login')
{

	//user session parameters and info, updates
	$response['user'] = [
	'ID'=> intval($userID),
	'name'=> (($userID>10000)?'Performer':'User') . $userID,
	'sessionID'=> intval($sessionID),
	'sessionKey'=> $sessionKey,
	'loggedIn' => true,
	'balance' => 100,
	'avatar' => VW_H5V_URL .'images/avatar.png',
	];


	//on login check if any private request was active to restore
	//return private room/session if active, depending on integration

	$response['room'] = appPublicRoom($roomID, $userID, $options, 'Login success!');

	//config params, const
	$response['config'] = [
	'wss' => $options['wsURLWebRTC'] ?? '',
	'application' => $options['applicationWebRTC'] ?? '',

	'videoCodec' =>  $options['webrtcVideoCodec'] ?? '',
	'videoBitrate' =>  $options['webrtcVideoBitrate'] ?? 0,
	'audioBitrate' =>  $options['webrtcAudioBitrate'] ?? 0,
	'audioCodec' =>  $options['webrtcAudioCodec'] ?? '',
	'autoBroadcast' => false,
	'actionFullscreen' => true,
	'actionFullpage' => false,

	'serverURL' =>  VW_H5V_CALL,
	'nonce' => $sessionKey,
	'vwsAuth' => ['account'=>''],

	];

	$response['config']['text'] = appText(); //translations
	$response['config']['sfx'] = appSfx();

	$roomURL = demo_room_url($roomID);
	$response['config']['exitURL'] = $roomURL;

	//pass app setup config parameters
	if (is_array($options['appSetup']))
		if (array_key_exists('Config', $options['appSetup']))
			if (is_array($options['appSetup']['Config']))
				foreach ($options['appSetup']['Config'] as $key => $value)
					$response['config'][$key] = $value;


				if (VW_DEVMODE)
				{
					$response['config']['cameraAutoBroadcast'] = '0';
					$response['config']['videoAutoPlay '] = '0';

				}

			if (!isset($isPerformer)) $response['config']['cameraAutoBroadcast'] = '0';

			//$response['config']['devMode '] = '1';
			$response['config']['loaded'] = true;

}
//end: task==login


//room
$roomName = 'Room' . $roomID;
$userName = (($userID>10000)?'Performer':'User') . $userID;
$ztime = time();



$needUpdate = array();

//process app task (other than login)
switch ($task)
{

case 'login':
	break;

case 'tick':
	break;

case 'options':
	break;

case 'update':
	break;


case 'recorder_upload':
	$response = array_merge($response, demo_upload($roomName));
	break;

default:
	$response['warning'] = 'Not implemented in this integration: ' . $task;

}


$response['startTime'] = 0;
$response['messages'] = []; //messages list
$response['timestamp'] = $ztime; //update time
$response['lastMessageID'] = 0;
$response['roomUpdate']['users'] = [];


// Never permit appSetup overrides to expose an account-level credential.
unset($response['config']['vwsToken'], $response['config']['token']);

//send response to app
echo json_encode($response);
