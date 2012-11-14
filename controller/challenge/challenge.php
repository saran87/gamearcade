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
					if($this->canChallenge($userId,$partnerId,$gameId)){
						$challengeAccess = new Challenges();
						$data["data"]	 = $challengeAccess->createChallenge($userId,$partnerId,$gameId);
					}
					else{
						$data["error"] = "An active challenge exist already. Finish that challenge first";
					}
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
		public function getChallengeStatus($isReturn = false){
		
			$data = array();
			if($this->authenticate()){
				$userId = $_SESSION['id'];
				$challengeId = getQueryString("challengeId");
				
				if( $challengeId != ""){	
						$challengeAccess = new Challenges();
						$data["data"]	 = $challengeAccess->getChallengeStatus($challengeId);
					
						if(!$data["data"]["error"]){
						$player1 = $data["data"]["player1_id"];
						$player2 = $data["data"]["player2_id"];
					
						$userAccess = new Users();
						
						$userStatus = $userAccess->getUserStatus($player1);
						$userStatus2 = $userAccess->getUserStatus($player2);
					
						if($userStatus["online_status"] != "online" || $userStatus2["online_status"] != "online"){
						
							$data["data"]["error"] = "Player not active, try challenging another player";
						}
					}					
				}
				else{
					$data["error"]  = "missing required field challengeId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}

			if(!$isReturn)
				ouputJson($data);
			else
				return $data;
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
					if( $challengeId != ""){
						$data = $this->getChallengeStatus(true);
						if(!$data["data"]["error"]){
							
								$status = Challenges::$ACCEPTED;
								
								$challengeAccess = new Challenges();
								$data["data"]["update_status"]	 = $challengeAccess->updateChallenge($userId,$challengeId,$status);
								$data["data"]["status"] = Challenges::$ACCEPTED;
							}
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
		/*
		* cancel Challenge 
		*
		*/
		public function cancelChallenge(){
				
			$data = array();
			if($this->authenticate()){
				$userId = $_SESSION['id'];
				$challengeId = getQueryString("challengeId");
				
				if( $challengeId != ""){	
						$challengeAccess = new Challenges();
						$data["data"]	 = $challengeAccess->deleteChallenge($challengeId,$userId);
				}else{
						$data["error"]  = "missing required field challengeId";
					}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		
		/*
		* can Challenge check if the user can challenge other player
		*
		*/
		private function canChallenge($userId,$partnerId,$gameId){
				
			$canChallenge = true;
			
			$challengeAccess = new Challenges();
			$data	 = $challengeAccess->getStatus($userId,$partnerId,$gameId);
			
			if($data){
				if($data["status"] != "No active game"){
					if($data["status"] != Challenges::$FINISHED){
						$canChallenge = false;
					}
				}
			}
			
			return $canChallenge;
		}
	}
	
?>