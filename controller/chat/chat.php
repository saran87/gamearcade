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
		
			//require_once(ROOT_PATH . "/dataAccess/chat.php");
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
		
		/**** private methods ****/
		/*
		* Create a new account for user
		*
		*/
		
	}
	
?>