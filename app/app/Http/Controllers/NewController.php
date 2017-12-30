<?php
/**
 *  Copyright (c) Microsoft. All rights reserved. Licensed under the MIT license.
 *  See LICENSE in the project root for license information.
 *
 *  PHP version 5
 *
 *  @category Code_Sample
 *  @package  php-connect-sample
 *  @author   Caitlin Bales <caitlin.bales@microsoft.com>
 *  @license  MIT License
 *  @link     http://github.com/microsoftgraph/php-connect-sample
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Microsoft\Graph\Connect\Constants;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Http\GraphResponse;
use Microsoft\Graph\Model;

/**
 *  Handles the creation of the email and sends the request
 *  to Microsoft Graph
 *
 *  @class    EmailController
 *  @category Code_Sample
 *  @package  php-connect-sample
 *  @author   Caitlin Bales <caitlin.bales@microsoft.com>
 *  @license  MIT License
 *  @link     http://github.com/microsoftgraph/php-connect-sample
 */
class NewController extends Controller 
{ 
	/**
	* Queries Microsoft Graph to get the current logged-in
	* user's info
	*
	* @return Microsoft\Graph\Model\User The current user
	*/
	public function getMe()
	{
		if (session_status() == PHP_SESSION_NONE)
			session_start();

		$graph = new Graph();
		$graph->setAccessToken($_SESSION['access_token']);

		$me = $graph->createRequest("get", "/me")
					->setReturnType(Model\User::class)
					->execute();
		return $me;
	}

	/**
	* Email view
	*
	* @return view /email
	*/
	public function showUserInfo()
	{
		$me = $this->getMe();

		return view('new', array('name' => $me->getGivenName(), 'status' => null, 'debag' => "test"));
	}

	public function sendMeetingMail($mail_address, $_title, $_text){
		
		if (session_status() == PHP_SESSION_NONE)
			session_start();
		$graph = new Graph();
		$graph->setAccessToken($_SESSION['access_token']);
		$me = $this->getMe();

		//Create a new sender object
		$sender = new Model\Recipient();
		$sEmail = new Model\EmailAddress();
		$sEmail->setName($me->getGivenName());
		$sEmail->setAddress($me->getMail());
		$sender->setEmailAddress($sEmail);
		
		//Create a new recipient object
		$recipient = new Model\Recipient();
		$rEmail = new Model\EmailAddress();
		$rEmail->setAddress($mail_address);
		$recipient->setEmailAddress($rEmail);

		//Set the body of the message
		$body = new Model\ItemBody();
		$body->setContent($_text);
		$body->setContentType(Model\BodyType::TEXT);

		//Create a new message
		$mail = new Model\Message();
		$mail->setSubject($_title)
			 ->setBody($body)
			 ->setSender($sender)
			 ->setFrom($sender)
			 ->setToRecipients(array($recipient));

		//Send the mail through Graph
		$request = $graph->createRequest("POST", "/me/sendMail")
						 ->attachBody(array ("message" => $mail));
		$request->execute();
		
	}

	public function create_note_meeting(){
		if(strpos($_POST['event_title'], 'meeting') !== false){

		$graph = new Graph();
		$graph->setAccessToken($_SESSION['access_token']);

		$request = $graph->createRequest("POST", "/me/onenote/notebooks")
						 ->attachBody(array ("displayName" => $_POST['event_title']));

		$request->setReturnType(Model\ItemReference::class)
		        ->execute();

		$participant = $this->getParticipantList($_POST['participant']);

		foreach($participant as $value){
			$this->sendMeetingMail($value, $_POST['event_title'], $_POST['mail_text']);
		}

			return "Note create (Name : " . $_POST['event_title'] ." )";
		}

		
	}

	public function createEvent(){

		if (session_status() == PHP_SESSION_NONE)
			session_start();
		$graph = new Graph();
		$graph->setAccessToken($_SESSION['access_token']);
		$me = $this->getMe();

		//Set the body of the message
		$body = new Model\ItemBody();
		$body->setContentType(Model\BodyType::HTML);
		$body->setContent($_POST['event_description']);
		
		$StartDateTime = $_POST['start_date'] . "T" . $_POST['start_time'];
		$EndDateTime = $_POST['end_date'] . "T" . $_POST['end_time'];
		

		$stareTime = new Model\DateTimeTimeZone();
		$stareTime -> setDateTime("$StartDateTime");
		$stareTime -> setTimeZone("Tokyo Standard Time");

		$endTime = new Model\DateTimeTimeZone();
		$endTime -> setDateTime("$EndDateTime");
		$endTime -> setTimeZone("Tokyo Standard Time");

		$event = new Model\Event();
		$event ->setSubject($_POST['event_title'])
			   ->setBody($body)
			   ->setStart($stareTime)
			   ->setEnd($endTime);

		$request = $graph->createRequest("POST", "/me/events")
						 ->attachBody($event);
		$request->execute();

		$check = $this -> create_note_meeting();

		//Return to the email view
		return view('new', array('name' => $me->getGivenName(), 'email' => $me->getMail(), 'status' => 'success', 'debag' => $check));
	}

	public function getParticipantList($participant){

		$array = explode("\n", $participant); 
		$array = array_map('trim', $array);
		$array = array_filter($array, 'strlen'); 
		$array = array_values($array);

		return $array;
	}

}
