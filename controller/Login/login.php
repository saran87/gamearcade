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
	
		private $title = "Game Arcade";
		
		function __construct(){
		
			require_once(ROOT_PATH . "/dataAccess/users.php");
		
		}
		public function test(){
		
			$this->setViewData('title',$this->title);
		}
		
		public function index(){
		
			
			$this->setViewData('title',$this->title);
		}
		
		public function register(){
		
			$data = array();
			$this->setViewData('title',$this->title);
			$data = $this->createNewAccount();
			$this->setViewData('data',$data);
		}
		
		/**** private methods ****/
		/*
		* Create a new account for user
		*
		*/
		
		private function createNewAccount(){
		
			$name     = isset($_POST['name']) ? $_POST['name'] : '';
			$email    = isset($_POST['email']) ? $_POST['email'] : '';
			$password = isset($_POST['password']) ? $_POST['password'] : '';
			
			
			if($password != '' && $email != '' && $name != ''){
				
				if(validateEmail($email)){
				
					if($this->validateName($name)){
					
						if($this->validatePassword($password)){
						
							$userAccess = new Users();
							$data	 	= $userAccess->createNewUser($name,$email,$password);
						
						}	
						else{
							$data['error'] = "Length of password should be greater or equal to 8";
						}
					}
					else{
						$data['error'] = "Only alphabets are allowed in name field";
					}
				}
				else{
					$data['error'] = "Not a valid email address";
				}
			}
			
			return $data;
		}
		
	
		
		/*
		* Validate name entered in the registration form
		* Checks if it contains only alpha characters
		* Returns true if name is valid and false if not
		*/
		
		private function validateName($name){
		
			$isValid = false;

			$nameReg = "/^[a-zA-Z]+\s*[a-zA-Z]*$/i";
			$isValid = preg_match($nameReg,$name);
			
			return $isValid;		
		}
		
		/*
		* Validate email address
		*
		*/
		
		private function validatePassword($password){
			
			$isValid = false;
		
			if(strlen($password)>=8){
			
				$isValid = true;
			}
			
			return $isValid;
		}
		
	}
	
?>