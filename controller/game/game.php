<?php

	/**
	 * The game.php is where all the request for game creation and game turns 
	 * are handled and dispatched to appropriate methods .
	 *
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Game Class
	 *
	 * This class contains functions that handles and process the request 
	 * related to game play 
	 *
	 * @author	Saravana Kumar
	 */
	 
	class Game extends BaseController{
	
		private $title = "Game Arcade";
		
		function __construct(){
	
			require_once(ROOT_PATH . "/dataAccess/challenges.php");
			require_once(ROOT_PATH . "/dataAccess/users.php");
			require_once(ROOT_PATH . "/dataAccess/boards.php");
		
		}
		
		public function index(){
		
			$data = array();
			if($this->authenticate()){
				$userId = $_SESSION['id'];
				$boardId = getQueryString("boardId");
				if( $boardId != "" ){	
					
					$board = new Boards();
					$data["data"] = $board->getDetails($boardId,$userId);
				}
				else{
					$data["data"]["error"]  = "missing required field boardId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		//createGame
		public function getGame(){
		
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
							else{
								$board = new Boards();
								//see if any board is present
								$data["data"]["boardId"] = $board->getBoard($challengeId);
								//if board id is not there, create a new board
								if(!$data["data"]["boardId"])
									$data["data"]["boardId"] = $board->createBoard($challengeId,$player1);
								}
							}
					}					
				else{
					$data["data"]["error"]  = "missing required field challengeId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
	}
	
?>