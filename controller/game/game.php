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
			require_once(ROOT_PATH . "/dataAccess/scoreBoardAccess.php");
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
					
					if($data["data"]["winner_id"] !=0){
						if($userId == $data["data"]["winner_id"]){
								
								$data["data"]["message"] = "you won the challenge";
						}
						else{
								$data["data"]["message"] = "you lost the challenge";
						}
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
					$data["data"]["isMyTurn"] = true;
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
						
						$newState = $this->updateBoardState($data["data"]["cur_state"],$column,$playerId,$data);
						if($newState != "error"){
							
							$data["data"]["updated"] = $board->updateBoard($newState,$newTurnId,$boardId);
							if($data["data"]["isWon"]){
							
								$challenge = new Challenges();
								$data["data"]["challengeUpdated"] = $challenge->updateWinner($challengeId,$userId);
								
								
								$scoreBoard = new ScoreBoardAccess();
								$score = $scoreBoard->getDetails($userId);	
								if($score["error"]){
									$scoreBoard->createRecord($userId);
								}
								
								$currentscore = isset($score["score"]) ? $score["score"] : 0;
								$totalGames = isset($score["total_games"]) ? $score["total_games"] : 0;
								$num_wins = isset($score["num_wins"]) ? $score["num_wins"] : 0;
								$time = isset($score["best_time"]) ? $score["best_time"] : time();
								
								$currentscore += 10;
								$totalGames++;
								$num_wins++;
								
								$scoreBoard->updateScoreBoard($userId,$currentscore,$totalGames,$num_wins,$time);
								
							}
							$data["data"]["isMyTurn"] = false;
							$data["data"]["cur_state"] = $newState;
						}
						else{
							if(!$data["data"]["error"]){
								$data["error"] = "Error occurred while updating the board ";
							}
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
		
		/*
		* Reset game 
		*
		*/
		public function resetGame(){
		
			$data = array();
			if($this->authenticate()){
				
				$userId = $_SESSION['id'];
				$challengeId = getQueryString("challengeId");
				$boardId = getQueryString("boardId");
				if( $boardId != ""  && $challengeId != ""){
					
					$board = new Boards();
					$data["data"] = $board->getDetails($boardId,$userId);
					
					if($data["data"]["current_turn"]){
						
						$player1 = $data["data"]["player1_id"];
						$player2 = $data["data"]["player2_id"];
						
						if( $userId == $player1){
							$playerId = 1;
						}else{
							$playerId = 2;
						}
						
						$newState = $this->getBoardStateString($this->intializeBoard());
						if($newState != "error"){
							
							$data["data"]["updated"] = $board->updateBoard($newState,$data["data"]["current_turn"],$boardId);
							
							if($data["data"]["current_turn"] != $userId){
								$data["data"]["isMyTurn"] = false;
							}else{
								$data["data"]["isMyTurn"] = true;
							}
							
							$data["data"]["cur_state"] = $newState;
						}
						else{
							$data["error"] = "Error occurred while updating the board ";
						}		
					}
					else{
						$data["error"] = "Not your turn";
					}
				}
				else{
					$data["error"]  = "missing required field challengeId, boardId";
				}
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		
		}
		
		private function updateBoardState($state,$column,$playerId,&$data){
			
			if(!$state){
				$board 	= $this->intializeBoard();
			
			}else{
			
				$board 	=	$this->getBoardFromString($state);
			}
			
			$isBoardSet = false;
			for($i = count($board) - 1; $i >= 0; $i--){
				if( $column < count($board[$i])){
					if($board[$i][$column] == 0){
						$board[$i][$column] = $playerId;	
						$isBoardSet = true;
						$data["data"]["isWon"] = $this->isWinningShot($board,$i,$column);
						break;
					}
				}
				else{
						
						return "error";
				}
			}
			
			if(!$isBoardSet){
				$data["data"]["error"] = "no more moves in the board";
				return "error";
			}
						
			$str 	= $this->getBoardStateString($board);	
			return $str;
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
		
		private function isWinningShot($board,$row,$column){
			
			
			$totalRow = count($board);
			$totalColumn = count($board[$row]);
			
			$playerId = $board[$row][$column];
			$tempRow = $row;
			$total = 0;
			//move downwards
			while($tempRow < $totalRow ){
				
				if($playerId == $board[$tempRow][$column]){
					
					$total++;
					$tempRow++;
				}
				else{
					break;
				}
			}
			
			if($total>=4){
				return true;
			}
			//see horizontally
			$tempColumn = $column;
			$total = 0;
			//move rightwards
			while($tempColumn < $totalColumn ){
				
				if($playerId == $board[$row][$tempColumn]){
					
					$total++;
					$tempColumn++;
				}
				else{
					break;
				}
			}
			
			//move downwards
			$tempColumn = $column-1;
			while($tempColumn > 0 ){
				
				if($playerId == $board[$row][$tempColumn]){
					
					$total++;
					$tempColumn--;
				}
				else{
					break;
				}
			}
			
			if($total>=4){
				return true;
			}
			//Checking for 				-
			//						-
			//				 	-
			//				-
			//move diagonally downwards --->front
			$tempRow = $row;
			$tempColumn = $column;
			$total = 0;
			while($tempRow<$totalRow  && $tempColumn < $totalColumn){
				
				if($playerId == $board[$tempRow][$tempColumn]){
					$total++;
					$tempRow++;
					$tempColumn++;
				}
				else{
					break;
				}
			}
			//move diagonally upwards  <---- back
			$tempRow = $row - 1;
			$tempColumn = $column - 1;
			while($tempRow >= 0  && $tempColumn  >= 0 ){
				
				if($playerId == $board[$tempRow][$tempColumn]){
					$total++;
					$tempRow--;
					$tempColumn--;
				}
				else{
					break;
				}
			}
			if($total>=4){
				return true;
			}
			
			//Checking for -
			//				-
			//				 -
			//				  -
			//move diagonally downwards ---> back
			$tempRow = $row;
			$tempColumn = $column;
			$total = 0;
			while($tempRow<$totalRow  && $tempColumn >= 0){
				
				if($playerId == $board[$tempRow][$tempColumn]){
					$total++;
					$tempRow++;
					$tempColumn--;
				}
				else{
					break;
				}
			}
			//move diagonally upwards infront
			$tempRow = $row - 1;
			$tempColumn = $column + 1;
			while($tempRow >= 0  && $tempColumn  < $totalColumn ){
				
				if($playerId == $board[$tempRow][$tempColumn]){
					$total++;
					$tempRow--;
					$tempColumn++;
				}
				else{
					break;
				}
			}
			if($total>=4){
				return true;
			}
			
			return false;
		}
	}
	
?>