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
					
					if($userId == $data["data"]["current_turn"]){
						$data["data"]["isMyTurn"] = true;
					}
					else{
						$data["data"]["isMyTurn"] = false;
					}
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
								if( $userId == $player1){
									$data["data"]["playerId"] = 1;
								}else{
									$data["data"]["playerId"] = 2;
								}
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
		//updateGame
		public function updateGame(){
		
			$data = array();
			if($this->authenticate()){
				
				$userId = $_SESSION['id'];
				$challengeId = getQueryString("challengeId");
				$boardId = getQueryString("boardId");
				$column = getQueryString("column");
				if( $boardId != ""  && $challengeId != "" && $column != ""){
					
					$board = new Boards();
					$data["data"] = $board->getDetails($boardId,$userId);
					
					if($userId == $data["data"]["current_turn"]){
						
						$player1 = $data["data"]["player1_id"];
						$player2 = $data["data"]["player2_id"];
						
						if( $userId == $player1){
							$playerId = 1;
							$newTurnId = $player2;
						}else{
							$playerId = 2;
							$newTurnId = $player1;
						}
						
						$newState = $this->updateState($data["data"]["cur_state"],$column,$playerId);
						if($newState != "error"){
							$data["data"]["updated"] = $board->updateBoard($newState,$newTurnId,$boardId);
							$data["data"]["isMyTurn"] = false;
						}
						else{
						
						}		
					}
					else{
						$data["error"] = "Not your turn";
					}
					
				}
				else{
					$data["error"]  = "missing required field challengeId, boardId, column";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
		
		private function updateState($state,$column){
			
			if(!$state){
				$board 	= $this->intializeBoard();
			
			}else{
			
				$board 	=	$this->getBoardFromString($str);
			}
			
			for($i = count($board) - 1; $i >= 0; $i--){
				if( $column < count($board[$i])){
					if($board[$i][$column] == 0){
						$board[$i][$column] = $playerId;	
						break;
					}
				}
				else{
						
						
				}
			}
			
			
			
			$str 	= $this->getBoardStateString($board);	
			print_r($board);
		}
		
		private function intializeBoard(){
		
			$board = array();
			
			for($row = 0; $row < 6 ; $row++){
				$board[$row] = array();
				for($column = 0; $column < 7; $column++){
					
					$board[$row][$column] = 0;
				
				}
			}
			
			return $board;
		}
		
		private function getBoardStateString($board){
				
				$str = "";
				
				for($i = 0; $i< count($board); $i++){
					$str .= implode($board[$i],"-");
					$str .= "|";
				}
				echo $str;
				return $str;				
		}
		
		private function getBoardFromString($str){
		
		
			$rowArray = explode("|",$str);
			
			for($i = 0; $i< count($rowArray); $i++){
				if(trim($rowArray[$i])){
					$rowArray[$i] = explode("-",$rowArray[$i]);
				}
				else{
					unset($rowArray[$i]);
				}
			}
			
			return $rowArray;
		}
	}
	
?>