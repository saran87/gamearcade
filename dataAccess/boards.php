<?php
require_once("library/DataAccess.php");

//Class to hold and Perform  Board related activites
class Boards extends DataAccess{
	
	//Constructor
	function __construct(){
		parent::__construct();
	}
	
	 
	/*
	* create a challenge
	*			Creates challenge for given player Id's
	*
	*/
	public function createBoard($challengeId,$playerId){
		
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `boards`(`board_id`, `challenge_id`, `current_turn`, `cur_state`) VALUES (?,?,?,?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array(NULL,$challengeId,$playerId,NULL);
		
		//specify the types of data to be binded 
		$types = array("i","i","i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->getInsertId();
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		
		return $data;
	}
	
	/*
	* getDetails of a board 
	*
	*/
	public function getDetails($boardId,$userId){
	

		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT B.`board_id`, B.`challenge_id`, B.`current_turn`, B.`cur_state`, C.`player1_id`, C.`player2_id`,C.`winner_id` FROM `boards` B , `challenges` C WHERE B.`board_id` = ? AND B.`challenge_id` = C.`challenge_id` AND (C.`player1_id` = ? OR C.`player2_id` = ?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($boardId,$userId,$userId);
		
		//specify the types of data to be binded 
		$types = array("i","i","i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_array();
			
			if(!$data){
				$data['error'] = "Board doesn't exist or you don't have access to the board";
			}
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		return $data;
	}
	
	/*
	* getDetails of a board 
	*
	*/
	public function getBoard($challengeId){
	

		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT `board_id` FROM `boards`  WHERE `challenge_id` = ? ";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($challengeId);
		
		//specify the types of data to be binded 
		$types = array("i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_array();
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
			$data['error'] = "Server error occurred";
		}
		
		return $data["board_id"];
	}
	
	public function updateBoard($state,$playerId,$boardId){
		
		//array to hold the data retrieved
		$data = array();
	
		$query = "UPDATE `boards` SET `current_turn`=?,`cur_state`=? WHERE `board_id`=?";
	
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array( $playerId,$state,$boardId);
		
		//specify the types of data to be binded 
		$types = array("i","s","i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$val = $this->database->getAffectedRows();
			if($val < 1){
				$data['error'] = "No changes done to board";
			}else{
				$data["updated"] = "success";
			}
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
				$data['error'] = "Server error occurred";
		}
		
		return $data;
	
	}
}


?>

