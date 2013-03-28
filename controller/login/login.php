<?php

	/**
	 * The login.php is where all the request for login and account creating 
	 * are handled and dispatched to appropriate methods .
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
			require_once(ROOT_PATH . "/dataAccess/chatDataAccess.php");
			require_once(ROOT_PATH . "/dataAccess/users.php");
		
		}
		public function test(){
		
			$this->setViewData('title',$this->title);
		}
		
		public function index(){
		
			$data = array();
			$this->setViewData('title',$this->title);
			$data = $this->verifyUser();
			
			$this->setViewData('data',$data);
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
							
							//if user account is created successfully
							if(isset($data['id_users'])){
								//add new users to public chat room
								$chatDataAccess = new ChatDataAccess();
								$chatDataAccess->addParticipant(70,$data['id_users']);
								
								$authorizer = new Authorizer();
								//generate the token
								$token = $authorizer->GenerateToken($data['id_users']);
								//Send token to the client
								$authorizer->SendTokenToClient($token);

								//Set username and id to session
								$_SESSION['name'] = $data['name'];
								$_SESSION['id']   = $data['id_users'];
							}
						
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
		
		/**** private methods ****/
		/*
		* Verify the user account
		*
		*/
		
		private function verifyUser(){
		
			$email    = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
			$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
			
			
			if($password != '' && $email != '' ){
				
				if(validateEmail($email)){
					
						if($this->validatePassword($password)){
						
							$userAccess = new Users();
							$data	 	= $userAccess->getUserDetails($email,$password);
							
							//if user account is created successfully
							if(isset($data['id_users'])){
								
								$authorizer = new Authorizer();
								//generate the token
								$token = $authorizer->GenerateToken($data['id_users']);
								//Send token to the client
								//$authorizer->SendTokenToClient($token);
								setcookie("token12", $token, time() + 6000,'/gamearcade', "saravananadar.com", 0, true);
								$data["token"] = $token;
								//var_dump($_COOKIE);
								//Set username and id to session
								$_SESSION['name'] = $data['name'];
								$_SESSION['id']   = $data['id_users'];
								$status = "online";
								$data["status_changed"] = $userAccess->updateStatus($status,$_SESSION['id']);
							}
							else{
								$data['error'] = "Entered username and password doesn't match";
							}						
						}	
						else{
							$data['error'] = "Length of password should be greater or equal to 8";
						}
				}
				else{
					$data['error'] = "Not a valid email address";
				}
			}
			
			return $data;
		}
		
		
		/*
		* Logout user
		*
		*/
		public function logout(){
		
			$status = "offline";
			$userId = $_SESSION["id"];
			$userAccess = new Users();
			$data	 	= $userAccess->updateStatus($status,$userId);
					
			//if user status is modified successfully
			if(isset($data)){
				
				if(isset($_SESSION['name']))
						unset($_SESSION['name']);

				//unset the cookie
				setcookie("token", "", time()-3600);
				$this->setViewData('message',"Successfully logged out");
			}
			else{
				$this->setViewData('message',"Problem occurred while logging out");
			}
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