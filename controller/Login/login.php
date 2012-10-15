<?php

	/**
	 * The login.inc is where all the request are handled and dispatched to appropriate methods .
	 *
	 * The routines here dispatch control to the controller, which then directs 
	 * the controll to appropriate sections
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Login Class
	 *
	 * This class contains functions that handles and process the request 
	 * related to Login
	 *
	 * @author	Saravana Kumar
	 */
	class Login extends BaseController{
	
		public function test(){
		
			$title = "Connect4 - login";
			$this->setViewData('title',$title);
		}
		
		public function index(){
		
			$title = "Connect4 - login";
			$this->setViewData('title',$title);
		}
	}
	
?>