<?php

	
class ActivityDataAccess{

	
	Static function GetActivities(){
	
		// Get a singleton instance of the database class

		$db = Database::getInstance();

		$query = "SELECT * FROM Activity";


		$err = $db->doQuery($query);

		

		$str = "";

		if(empty($err)){

			$userArr = $db->fetch_all_array();
			return $userArr;
		}
	
	}

}

?>