<?php
 
 function InitializePage(){

	//Call database
	// Get a singleton instance of the database class
	/*$db = Database::getInstance();
	$query = "SELECT * 
				FROM  `User`
				LIMIT ?, ?";
				$page = 1;
				$countPerPage = 5;
	$offset = ($page - 1) * $countPerPage;
	$vars = array($offset,$countPerPage);
	$types = array("i","i");
	$err = $db->doQuery($query,$vars,$types);
	
	$str = "";
	if(empty($err)){
		$userArr = $db->fetch_all_array();
	}
	foreach($userArr as $index=>$user){
		echo $index . "-";
		print_r($user);
	}*/	//F3::set('DEBUG',1);/*F3::set('DB',	new DB(		'mysql:host=localhost;dbname=saravan1_ateam',		'saravan1_ateam',		'a-team@rit'	));*///$user=new Axon('User');//$user->load('idUser="1"');//print_r($user);
 }

?>