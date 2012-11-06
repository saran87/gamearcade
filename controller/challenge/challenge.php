<?php

	/**
	 * The challenge.php is where all the request for challenge creation and current challenges 
	 * are handled and dispatched to appropriate methods .
	 *
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Challenge Class
	 *
	 * This class contains functions that handles and process the request 
	 * related to challenge 
	 *
	 * @author	Saravana Kumar
	 */
	 
	class Challenge extends BaseController{
	
		private $title = "Game Arcade";
		
		function __construct(){
			require_once(ROOT_PATH . "/dataAccess/chatDataAccess.php");
			require_once(ROOT_PATH . "/dataAccess/challenges.php");
			require_once(ROOT_PATH . "/dataAccess/users.php");
		
		}
		
		public function index(){
		
			$data = array();
			if($this->authenticate()){
				$userId = $_SESSION['id'];
				$partnerId = getQueryString("partnerId");
				$gameId = getQueryString("gameId");
				if( $partnerId != ""  && $gameId != ""){	
					$challengeAccess = new Challenges();
					$data["data"]	 = $challengeAccess->createChallenge($userId,$partnerId,$gameId);
				}
				else{
					$data["error"]  = "missing required field partnerId or gameId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		//Get Challenge status gives current state of a challenge
		public function getChallengeStatus(){
		
			$data = array();
			if($this->authenticate()){
				$userId = $_SESSION['id'];
				$challengeId = getQueryString("challengeId");
				
				if( $challengeId != ""){	
					$challengeAccess = new Challenges();
					$data["data"]	 = $challengeAccess->getChallengeStatus($challengeId);
				}
				else{
					$data["error"]  = "missing required field challengeId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		//Get Challenge details
		public function getChallengeDetails(){
		
			$data = array();
			if($this->authenticate()){
					$userId = $_SESSION['id'];
					
					$challengeAccess = new Challenges();
					$data["data"]	 = $challengeAccess->getChallengeDetails($userId);
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		//Accept challenge
		public function acceptChallenge(){
		
			$data = array();
			if($this->authenticate()){
					$userId = $_SESSION['id'];
					$challengeId = getQueryString("challengeId");
					$status = Challenges::$ACCEPTED;
					if( $challengeId != ""){	
						$challengeAccess = new Challenges();
						$data["data"]	 = $challengeAccess->updateChallenge($userId,$challengeId,$status);
					}
					else{
						$data["error"]  = "missing required field challengeId";
					}
					
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
	}
	
?>