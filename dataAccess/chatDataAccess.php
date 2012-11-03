<?php
require_once("library/DataAccess.php");

//Class to hold and Perform  Activities
class ChatDataAccess extends DataAccess{
	
	//Constructor
	function __construct(){
		parent::__construct();
	}
	
	/*
	* Intialize chat 
	*			Creates chat id and creates new room for chat
	*
	*/
	public function intializeChat($userId,$partnerId,$message){
		
		//array to hold the data retrieved
		$data = array();
		
		$chatId = $userId . "_chat_" . $partnerId ;
		//another combination of chat Id
		$alternateId  =   $partnerId . "_chat_" . $userId; 
		
		$data = $this->isChatIdExists($chatId , $alternateId);
		
		if(isset($data)){
			//print_r($data);
			$data['participant1'] = $userId;
			$data['participant2'] = $partnerId;
			$data['chatWindowId'] = $partnerId;
		}
		else{
			$data = $this->createChatRoom($chatId , $alternateId);
			if(!isset($data['error'])){
	
				$roomId = $data['roomId'];
			
				$data['participant1'] = $this->addParticipant($roomId,$userId);
				if(!isset($data['error'])){
					$data['participant2'] = $this->addParticipant($roomId,$partnerId);
					$data['chat_id'] = $chatId;
					$data['chatWindowId'] = $partnerId;
				}
			}
		}
		return $data;
	}
	
	/*
	* Get user details 
	*
	*/
	public function createChatRoom($chatId){
	

		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `chat_rooms` (`room_id`,`chat_id`) VALUES (?, ?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array(NULL,$chatId);
		
		//specify the types of data to be binded 
		$types = array("i","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data['roomId'] = $this->database->getInsertId();
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Server error ocuured";
		}
		
		return $data;

	}
	
	
	/*
	* Add Participant to chat room
	*
	*/
	public function addParticipant($roomId,$participant,$type="single"){
	
	
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "INSERT INTO `chat_participants` (`room_id`,`participant_id`,`name`) VALUES (?, ?, ?)";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($roomId,$participant,$type);
		
		//specify the types of data to be binded 
		$types = array("i","s","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $participant;
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able to add participant";
		}
		
		return $data;
	}
	
	/*
	* remove participant from the chat room
	*
	*/
	public function removeParticipant($roomId,$participant,$type="single"){
	
	
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "DELETE FROM `chat_participants` WHERE  `room_id` = ? and `participant_id` = ? and  `name` =  ? ";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($roomId,$participant,$type);
		
		//specify the types of data to be binded 
		$types = array("i","s","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data["deleted"] = $participant;
			
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able to remove participant";
		}
		
		return $data;
	}
	
	/*
	* Add message to chat message
	*
	*/
	public function addMessage($chatId,$userId,$message){
	
	
		//array to hold the data retrieved
		$data = array();
		
		$users = new Users();
		$data = $users->getUserName();
		if(!isset($data['error'])){
		
			$userName = $data['name'];
			//query to insert user details into the users table
			$query = "INSERT INTO `chat_messages`(`message_id`, `chat_id`, `message`, `user_id`, `user_name`, `timestamp`) VALUES (?,?,?,?,?,?)";
			$timeStamp =date('Y-m-d H:i:s', time()) ;
			
			//build the vaariables array which holds the data to bind to the prepare statement.
			$vars = array(NULL,$chatId,$message,$userId,$userName,$timeStamp);
			
			//specify the types of data to be binded 
			$types = array("s","s","s","i","s","s");
		
			//excute the query 
			$err = $this->database->doQuery($query,$vars,$types);
			
			//check if any error occurred 
			if(empty($err)){
				
				$data["message_id"] = $this->database->getInsertId();
				$data["user_name"] = $data["name"];
				$data["chatId"]	   = $chatId;
				$data["message"]   = $message;
				$data["timestamp"] = $timeStamp;
				
			}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
				$data['error'] = "Not able to send the message, Try again";
			}
		}
		return $data;
	}
	
	/*
	* get messages for user
	*
	*/
	public function getMessages($userId){
	
	
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT  A.`message_id` ,  A.`chat_id` , A. `message` ,  A.`user_id` , A. `user_name` ,  A.`timestamp` 
					FROM  `chat_messages` A , `chat_rooms` B, `chat_participants` C
					WHERE  A.`timestamp` > ?
					AND  A.`chat_id` = B.`chat_id` AND B.`room_id` = C.`room_id` AND C.`participant_id` = ?";
					
		
		
		//intialize timestamp to before 30 seconds
		$timeStamp =date('Y-m-d H:i:s', time() - 30) ;
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($timeStamp,$userId);
		
		//specify the types of data to be binded 
		$types = array("s","i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_all_array();
			
		}else{
			ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able get messages, Try again";
		}
		
		return $data;
	}
	
	
	/*
	* get roomIds user belongs too
	*
	*/
	public function getRooms($userId){
	
	
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT `roomId` FROM `chat_participants` where `participant_id` = ?";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($userId);
		
		//specify the types of data to be binded 
		$types = array("i");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_all_array();
			
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able to send the message, Try again";
		}
		
		return $data;
	}
	
	public function getChatIdsForRooms($rooms){
	
		//convert the rooms array to string of roomids with comma seperated
		$roomIdString = "(" . explode($rooms,",") . ")";
		
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT `chat_id` FROM `chat_rooms` WHERE  `room_id` IN ?";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($roomIdString);
		
		//specify the types of data to be binded 
		$types = array("s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$chatIds = $this->database->fetch_all_array();
			
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able to send the message, Try again";
		}
		
		return $data;
	}
	
	public function getMessagesForChatIds($chatIds){
	
			$data = array();
			
			//convert the rooms array to string of roomids with comma seperated
			$chatIdString = "(" . explode($chatIds,",") . ")";
			
			//query to insert user details into the users table
			$query = "SELECT `message_id`, `chat_id`, `message`, `user_id`, `timestamp` FROM `chat_messages` WHERE chat_id IN ? and timestamp > ? GROUP BY chat_id";
			
			//build the vaariables array which holds the data to bind to the prepare statement.
			$vars = array($chatIdString, time()-10);
			
			//specify the types of data to be binded 
			$types = array("s","s");
		
			//excute the query 
			$err = $this->database->doQuery($query,$vars,$types);
			
			//check if any error occurred 
			if(empty($err)){

				$data = $this->database->fetch_all_array();
			
			}else{
					ErrorHandler::HandleError(DB_ERROR,$err);
					$data['error'] = "Not able to send the message, Try again";
			}
			return $data;
	}
	
	/**
	* Is chat Id exists 
	*
	*
	*
	*/
	private function isChatIdExists($chatId, $alternateId){
		
		//array to hold the data retrieved
		$data = array();
		
		//query to insert user details into the users table
		$query = "SELECT chat_id, room_id
									FROM  `chat_rooms` 
									WHERE chat_id =  ?
									OR chat_id =  ?
									GROUP BY chat_id
									LIMIT 0 , 1";
		
		//build the vaariables array which holds the data to bind to the prepare statement.
		$vars = array($chatId, $alternateId);
		
		//specify the types of data to be binded 
		$types = array("s","s");
	
		//excute the query 
		$err = $this->database->doQuery($query,$vars,$types);
		
		//check if any error occurred 
		if(empty($err)){
			
			$data = $this->database->fetch_array();
			
			if(isset($data)){
				
			}
			
		}else{
				ErrorHandler::HandleError(DB_ERROR,$err);
		
				$data['error'] = "Not able to send the message, Try again";
		}
		
		return $data;
	
	}
	
	
}


?>

