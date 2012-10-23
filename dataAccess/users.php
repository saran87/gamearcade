<?php
include("library/DataAccess.php");

//Class to hold and Perform  Activities
class Users extends DataAccess{
	
	
	function __construct(){
		parent::__construct();
	}
	
	public function createNewUser($name, $email, $password){
		
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `users` (`id_users`,`name`, `email`, `password`,`cur_game_id`, `online_status`) VALUES (?, ?, ?, ?, ?, ?)";

		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array(NULL,$name,$email,$password,NULL,"online");
		
		//specify the types of data to be binded 
		$types = array("i","s","s","s","i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			$insertID = $this->database->getInsertId();
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
			if(preg_match("/^Duplicate entry/i",$err)){
				$data['error'] = "You already have an account";
			}else{
				$data['error'] = "Some Error ocuured";
			}
		}
		
		return $data;
	}
			
}


?>

