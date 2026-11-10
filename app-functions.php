<?php
//app functions needed by app calls, depend on integration

//For more advanced implementations see WordPress plugin and its php source code:
//https://wordpress.org/plugins/ppv-live-webcams/
//https://plugins.svn.wordpress.org/ppv-live-webcams/trunk/inc/h5videochat.php 


require_once __DIR__ . '/demo-security.php';
function demo_state_path($path) {
    if (!is_string($path) || !preg_match('/^[0-9]+_(sessions|room|messages)$/D', $path)) throw new RuntimeException('Invalid state key.');
    return demo_state_dir() . '/' . $path;
}
function varSave($path, $var) {
    if (is_array($var) && str_ends_with($path, '_messages') && count($var) > 200) $var = array_slice($var, -200, null, true);
    if (file_put_contents(demo_state_path($path), serialize($var), LOCK_EX) === false) throw new RuntimeException('Cannot save demo state.');
    chmod(demo_state_path($path), 0600);
}
function varLoad($path) {
    $file = demo_state_path($path);
    if (!is_file($file) || is_link($file) || filesize($file) > 2*1024*1024) return false;
    return unserialize(file_get_contents($file), ['allowed_classes'=>false]);
}
function arrayLoad($path) { $value = varLoad($path); return is_array($value) ? $value : []; }

// app parameter functions

	function __(  $text,  $domain = 'default' )
	{
		return 	$text;
	}

		 function path2url($file)
		{
			$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			return dirname($url) . '/' . str_replace( dirname(__FILE__) , '', $file);
		}
		
	 function appFail($message = 'Request Failed', $response = null)
	{
		//bad request: fail
		
		if (!$response) $response = array();

		$response['error'] = $message;

		$response['VideoWhisper'] = 'https://videowhisper.com';

		echo json_encode($response);

		die();
	}
	
	
	 function appSfx()
	{
		//sound effects sources
		
		$base = VW_H5V_URL. 'sounds/';
		
		
		return array(
			//
		);
	}
		
	function appText()
	{
		//implement translations

		//returns texts
			return array(
			'Send' => __('Send', 'ppv-live-webcams'),
				);
	}




function appPublicRoom($roomID, $userID, $options, $welcome ='')
{
	//public room parameters, specific for this user
	//depends on integration

	$room = array();

	$room['ID'] = $roomID;
	$room['name'] = 'Room' . $roomID;

	$room['performer'] = 'Performer' . (10000+$roomID);
	$room['performerID'] = (10000+$roomID);

	$isPerformer = ($userID == (10000+$roomID));

	//screen

	$room['screen'] = 'RecorderScreen';


	//$room['actionPrivate'] = !$isPerformer;
	$room['privateUID'] = 0;

	$room['actionID'] = 0;
	
	//custom buttons
	$actionButtons = array();

	$roomURL = demo_room_url($roomID);


	//_ will be added to target
	$actionButtons['exitDashboard'] = array('name'=> 'exitDashboard', 'icon'=>'close', 'color'=> 'red', 'floated'=>'right', 'target' => 'top', 'url'=> $roomURL, 'text'=>'Exit', 'tooltip'=> __('Exit', 'ppv-live-webcams'));
	$room['actionButtons'] = $actionButtons;	

	return $room;
}

