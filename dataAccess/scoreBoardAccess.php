<?php
require_once("library/DataAccess.php");

//Class to hold and Perform  Board related activites
class ScoreBoardAccess extends DataAccess{
	
	//Constructor
	function __construct(){
		parent::__construct();
	}
	
	 
	/*
	* create a challenge
	*			Creates challenge for given player Id's
	*
	*/
	public function createRecord($userId){
		
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `score_board`(`user_id`, `score`, `total_games`, `num_wins`, `best_time`) VALUES (?,?,?,?,?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($userId,0,0,0,NULL);
		
		//specify the types of data to be binded 
		$types = array("i","i","i","i","s");
	
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
	* getScore Details of a board 
	*
	*/
	public function getDetails($userId){
	

		//array to hold the data retrieved
		$data = array();
		
		if(isset($userId)){
			//query to insert user details into the users table
			$query = "SELECT `user_id`, `score`, `total_games`, `num_wins`, `best_time` FROM `score_board` WHERE `user_id` = ?";
			//build the vaariables array which holds the data to bind to the prepare statement.
			$vars = array($userId);
			
			//specify the types of data to be binded 
			$types = array("i");
		
			//excute the query 
			$err = $this->database->doQuery($query,$vars,$types);
		}else{
			//query to insert user details into the users table
			$query = "SELECT S.`user_id`, U.`name`, S.`score`, S.`total_games`, S.`num_wins`, S.`best_time` FROM `score_board` S, `users` U WHERE U.`id_users` = S.`user_id`";
			//excute the query 
			$err = $this->database->doQuery($query);
		}
		
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_all_array();
			
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
	* update score Board
	*
	*/
	public function updateScoreBoard($userId,$score,$totalGames,$num_wins,$time){
	

		//array to hold the data retrieved
		$data = array();
	
		$query = "UPDATE `score_board` SET `score`=?,`total_games`=?,`num_wins`=?,`best_time`=? WHERE `user_id`=?";
	
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array( $score,$totalGames,$num_wins,$time,$userId);
		
		//specify the types of data to be binded 
		$types = array("i","i","i","s","i");
	
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

