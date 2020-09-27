<?php
//app-call.php : called by app to communicate with web server (login, config, status, interactions)
//This is sample code for demostrating some features. Not designed for production.
//Integrate with own platform login and database system.
//For a functional turnkey integration see PaidVideochat.com turnkey site solution and plugin source code https://plugins.svn.wordpress.org/ppv-live-webcams/trunk/inc/h5videochat.php .

include_once 'settings.php';

include_once 'app-functions.php';

//session info received trough VideoWhisper POST var
if ($VideoWhisper = $_POST['VideoWhisper'])
{
	$userID = intval($VideoWhisper['userID']);
	$sessionID = intval($VideoWhisper['sessionID']);
	$roomID = intval($VideoWhisper['roomID']);
	$sessionKey = intval($VideoWhisper['sessionKey']);

	$privateUID = intval($VideoWhisper['privateUID']);
	$roomActionID = intval($VideoWhisper['roomActionID']);
}

$response['VideoWhisper'] = 'https://videowhisper.com';


$task = $_POST['task'];


if ($task != 'login')
{
	//verify user login, session validty
}

if ($task == 'login')
{

	//user session parameters and info, updates
	$response['user'] = [
	'ID'=> intval($userID),
	'name'=> (($userID>10000)?'Performer':'User') . $userID,
	'sessionID'=> intval($sessionID),
	'loggedIn' => true,
	'balance' => 100,
	'avatar' => VW_H5V_URL .'images/avatar.png',
	];


	//on login check if any private request was active to restore
	//return private room/session if active, depending on integration

	$response['room'] = appPublicRoom($roomID, $userID, $options, 'Login success!');

	//config params, const
	$response['config'] = [
	'wss' => $options['wsURLWebRTC'],
	'application' => $options['applicationWebRTC'],

	'videoCodec' =>  $options['webrtcVideoCodec'],
	'videoBitrate' =>  $options['webrtcVideoBitrate'],
	'audioBitrate' =>  $options['webrtcAudioBitrate'],
	'audioCodec' =>  $options['webrtcAudioCodec'],
	'autoBroadcast' => false,
	'actionFullscreen' => true,
	'actionFullpage' => false,

	'serverURL' =>  VW_H5V_CALL,

	];

	$response['config']['text'] = appText(); //translations
	$response['config']['sfx'] = appSfx();

	$roomURL = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . dirname(explode('?', $_SERVER['REQUEST_URI'], 2)[0]) . '/?r=' . $roomID;
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

			if (!$isPerformer) $response['config']['cameraAutoBroadcast'] = '0';

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


	if (!$roomName) appFail('No room for recording.');
	if (strstr($filename, ".php")) appFail('Bad uploader!');


	$mode = $_POST['mode'];		// audio/video	
	$scenario = $_POST['scenario'];	 // standalone/chat

	if (!$privateUID)  $privateUID = 0; //public room

	//generate same private room folder for both users
	if ($privateUID)
	{
		if ($isPerformer) $proom = $userID . "_" . $privateUID; //performer id first
		else $proom = $privateUID ."_". $userID;
	}

	$destination = 'uploads';
	if (!file_exists($destination)) mkdir($destination);

	$destination.="/$roomName";
	if (!file_exists($destination)) mkdir($destination);

	if ($proom)
	{
		$destination.="/$proom";
		if (!file_exists($destination)) mkdir($destination);
	}

	$response['_FILES'] = $_FILES;


	$allowed = array('mp3', 'ogg', 'opus', 'mp4', 'webm');

	$uploads = 0;
	$filename = '';

	if ($_FILES) if (is_array($_FILES))
			foreach ($_FILES as $ix => $file)
			{

				$filename = filter_var( $file['name'], FILTER_SANITIZE_STRING);

				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				$response['uploadRecLastExt'] = $ext;
				$response['uploadRecLastF'] = $filename;

				$filepath = $destination . '/' . $filename;

				if (in_array($ext, $allowed))
					if (file_exists($file['tmp_name']))
					{
						move_uploaded_file($file['tmp_name'], $filepath);
						$response['uploadRecLast'] = $destination . $filename;
						$uploads++;
					}
			}

		$response['uploadCount'] = $uploads;


	if (!file_exists($filepath))
	{
		$response['warning'] = 'Recording upload failed!';
	}

	break;

default:
	$response['warning'] = 'Not implemented in this integration: ' . $task;

}


$response['startTime'] = 0;
$response['messages'] = []; //messages list
$response['timestamp'] = $ztime; //update time
$response['lastMessageID'] = 0;
$response['roomUpdate']['users'] = [];


//send response to app
echo json_encode($response);
