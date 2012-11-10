<?php
require_once("library/DataAccess.php");

//Class to hold and Perform  Activities
class Challenges extends DataAccess{
	
	public static $WAITING = "waiting";
	public static $ACCEPTED = "accepted";
	public static $IN_PROGRESS = "in_progress";
	public static $FINISHED = "finished";
	public static $CANCELLED = "cancelled";
	
	//Constructor
	function __construct(){
		parent::__construct();
	}
	
	 
	/*
	* create a challenge
	*			Creates challenge for given player Id's
	*
	*/
	public function createChallenge($userId,$partnerId,$gameId){
		
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `challenges`(`challenge_id`, `game_id`, `player1_id`, `player2_id`, `status`) VALUES (?,?,?,?,?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array(NULL,$gameId,$userId,$partnerId,self::$WAITING);
		
		//specify the types of data to be binded 
		$types = array("i","i","i","i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data['challenge_id'] = $this->database->getInsertId();
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		
		return $data;
	}
	
	/*
	* Get challenge status 
	*
	*/
	public function getChallengeStatus($challengeId){
	

		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT `status` ,`player1_id`, `player2_id` FROM `challenges` WHERE `challenge_id` = ?";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($challengeId);
		
		//specify the types of data to be binded 
		$types = array("i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_array();
			if(!$data){
				$data['error'] = "Invalid gameId";
			}
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		return $data;

	}
	
	/*
	* Get challenge status 
	* Assue all games other than finished status
	*
	*/
	public function getStatus($userId,$partnerId,$gameId){
	

		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT `status` FROM `challenges` WHERE   `game_id` = ?
													AND ((
														`player1_id` = ?
													AND  `player2_id` = ?
														)
													OR (
														`player1_id` = ?
													AND  `player2_id` = ?
														)) AND `status` != ?";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($gameId,$userId,$partnerId, $partnerId, $userId,self::$FINISHED);
		
		//specify the types of data to be binded 
		$types = array("i","i","i","i","i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_array();
			if(!$data){
				$data['status'] = "No active game";
			}
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		return $data;

	}
	
	/*
	* Get Challenge Details for a user
	*
	*/
	public function getChallengeDetails($userId){
	
		//array to hold the data retrieved
		$data = array();
	
		$query = "SELECT C.`challenge_id`,G. `name`, U.`name` as player1,U2.`name` as player2,C.`timestamp`, C.`status` FROM `challenges` C , `users` U, `users` U2 , games G WHERE  (C.`player1_id` = ? OR C.`player2_id` = ? ) AND U.id_users = C.`player1_id` AND U2.id_users = C.`player2_id` AND C.`status` = ? AND G.`game_id` =  C.`game_id` GROUP BY C.`status`";
	
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($userId, $userId, self::$WAITING);
		
		//specify the types of data to be binded 
		$types = array("i","i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_all_array();
			if(!$data){
				$data['error'] = "No challenges";
			}
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error ocuured";
		}
		
		return $data;
	}
	
	/*
	* Update  challenge status
	*
	*/
	public function updateChallenge($userId,$challengeId, $status){
	
		//array to hold the data retrieved
		$data = array();
	
		$query = "UPDATE `challenges` SET `status`= ? WHERE `challenge_id`=? AND (`player1_id`=?  OR `player2_id`= ?)";
	
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($status, $challengeId, $userId, $userId);
		
		//specify the types of data to be binded 
		$types = array("s","i","i","i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->getAffectedRows();
			if(!$data){
				$data['error'] = "No challenges";
			}
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error occurred";
		}
		
		return $data;
	}
	
	/*
	* Update  challenge status
	*
	*/
	public function deleteChallenge($challengeId ,$userId){
	
		//array to hold the data retrieved
		$data = array();
	
		$query = "DELETE FROM `challenges` WHERE `challenge_id` = ? AND (`player1_id`=?  OR `player2_id`= ?)";
	
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array( $challengeId,$userId,$userId);
		
		//specify the types of data to be binded 
		$types = array("i","i","i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$val = $this->database->getAffectedRows();
			if($val < 1){
				$data['error'] = "you don't have access to cancel the challenge or Challenge does not exist";
			}else{
				$data = "success";
			}
			
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
				$data['error'] = "Server error occurred";
		}
		
		return $data;
	}
}


?>

