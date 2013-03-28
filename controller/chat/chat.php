<?php

	/**
	 * The chat.php is where all chat related requests are handled and dispatched to appropriate methods .
	 *
	 * The routines here dispatch control to the controller, which then directs 
	 * the controll to appropriate sections
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Chat Class
	 *
	 * This class contains functions that handles and process the request 
	 * related to Login
	 *
	 * @author	Saravana Kumar
	 */
	 
	class Chat extends BaseController{
	
		private $title = "Game Arcade";
		
		function __construct(){
		
			require_once(ROOT_PATH . "/dataAccess/chatDataAccess.php");
			require_once(ROOT_PATH . "/dataAccess/users.php");
		
		}
		
		public function index(){
		
			$data = array();
			
			$data = $this->verifyUser();
			
			$this->setViewData('data',$data);
		}
		
		public function getUserList(){
		
			$data = array();
			
			if($this->authenticate()){
				
				$userAccess = new Users();
				$data["data"]	 = $userAccess->getUserList();
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
			$this->setViewData('data',$data);
		}
		
		/*
		* Create a new room for chat and returns the chat id and room id
		* with participants
		*
		*/
		
		public function initChat(){
		

			$data = array();
			
			if($this->authenticate()){
				
				$userId = $_SESSION['id'];
				$partnerId = getQueryString("partnerId");
				if( $partnerId != "" ){	
					$chatDataAccess = new ChatDataAccess();
					$data["data"]	 = $chatDataAccess->intializeChat($userId,$partnerId);
				}
				else{
					$data["error"]  = "missing required field partnerId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
	
		}
		
		/*
		* sendMessage
		* Update the message send by the user to database
		*
		*/
		
		public function sendMessage(){
		

			$data = array();
			
			if($this->authenticate()){
				
				$userId = $_SESSION['id'];
				$chatId = getQueryString("chatid");
				$message = getQueryString("message");

				if($chatId != "" && $message != ""){
					$chatDataAccess = new ChatDataAccess();
					
					$data["data"]	 = $chatDataAccess->addMessage($chatId,$userId,$message);
				}
				else{
					$data['error']  = "Missing fields chatid and message";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
	
		}
		/*
		* getMessage
		* get the message sent to the user from database
		*
		*/
		
		public function getMessages(){
		

			$data = array();

			if($this->authenticate()){
				
				$userId = $_SESSION['id'];

				if(isset($userId) && $userId != ""){
					$chatDataAccess = new ChatDataAccess();
					
					$data["data"]	 = $chatDataAccess->getMessages($userId);
				}
				else{
					$data['error']['isLoginRequired']  = true;
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			
			ouputJson($data);
	
		}
		
	}
	
?>