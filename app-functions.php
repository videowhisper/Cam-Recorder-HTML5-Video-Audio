<?php
//app functions needed by app calls, depend on integration

//For more advanced implementations see WordPress plugin and its php source code:
//https://wordpress.org/plugins/ppv-live-webcams/
//https://plugins.svn.wordpress.org/ppv-live-webcams/trunk/inc/h5videochat.php 


//demo setup saves variables in plain files in uploads folder: integration should use framework database

		function varSave($path, $var)
		{
			if (!file_exists('uploads')) mkdir('uploads');
			file_put_contents('uploads/' . $path, serialize($var));
		}

		function varLoad($path)
		{
			if (!file_exists('uploads/' . $path)) return false;

			return unserialize(file_get_contents('uploads/' . $path));
		}
		
		function arrayLoad($path)
		{
			$res = varLoad($path);
			
			if (is_array($res)) return $res;
			else return array();
		}
		
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

	$roomURL = $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . dirname(explode('?', $_SERVER['REQUEST_URI'], 2)[0]) . '/?r=' . $roomID;


	//_ will be added to target
	$actionButtons['exitDashboard'] = array('name'=> 'exitDashboard', 'icon'=>'close', 'color'=> 'red', 'floated'=>'right', 'target' => 'top', 'url'=> $roomURL, 'text'=>'Exit', 'tooltip'=> __('Exit', 'ppv-live-webcams'));
	$room['actionButtons'] = $actionButtons;	

	return $room;
}

