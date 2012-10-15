<?php
	
	/**
	 * The common.inc is where common functions used by the whole site resides.
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Converts Object to an array
	 *
	 * @param	object	object that needs to converted to an array
	 * @return	array	converted object as an array
	 */
	 
	function toArray($object){
		foreach($object as $key=>$value){
			$array[] = $value;
		}
		return $array;
	}
	/**
	 * getBrowser
	 *
	 * @param	
	 * @return	returns the client browser name
	 */
	function getBrowser(){
		$agent = $_SERVER["HTTP_USER_AGENT"];
		if(preg_match('/chrome/i',$agent,$clientDetail)){
			return "chrome";
		}
		else if(preg_match('/msie/i',$agent,$clientDetail)){
			return "Internet Explorer";
		}
		else if(preg_match('/firefox/i',$agent,$clientDetail)){
			return "Mozilla FireFox";
		}
		else if(preg_match('/opera/i',$agent,$clientDetail)){
			return "opera";
		}
		else if(preg_match('/safari/i',$agent,$clientDetail)){
			return "Safari";
		}
		return "";
	}

	function getOS(){
		$agent = $_SERVER["HTTP_USER_AGENT"];
		if(preg_match('/windows/i',$agent,$clientDetail)){
			return "windows";
		}
		else if(preg_match('/mac/i',$agent,$clientDetail)){
			return "MAC OS";
		}
		else if(preg_match('/linux/i',$agent,$clientDetail)){
			return "Linux";
		}
	}

	function getAction(){
		//get the action from the GET request
		$action = isset($_GET["action"]) ? $_GET["action"] : 'index';
		
		//if action is not set then get it from post
		if( $action == '' ){
			$action = isset($_POST["action"]) ? $_POST["action"] : 'index';
		}
		
		return $action;
	}

	function getSection(){
		//get the action from the GET request
		$section = isset($_GET["s"]) ? $_GET["s"] : '';
		
		//if action is not set then get it from post
		if( $section == '' ){
			$section = isset($_POST["s"]) ? $_POST["s"] : '';
		}
		
		return $section;
	}

	

	function santizeInput($value){
		global $errorMessage;
		if(trim($value) ==""){
			$errorMessage[] = "You have entered invalid input";
			return false;
		}
		return true;
	}

	function santizePassword($input,$password){
		
		global $errorMessage;
		if(trim($input) != $password){
			$errorMessage[] = "You have entered invalid password";
			return false;
		}
		return true;
	}
	function crossSiteScriptingImg($value) {
		$reg = "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i";
		return preg_match($reg,$value);
	}
	function crossSiteScripting($value) {
		$reg = "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i";
		return preg_match($reg,$value);
	}
	
	/*
	* Redirect page
	* Redirects the page based on the option provided
	*/
	function redirectPage($option){
		
		if( $option == PAGE_NOT_FOUND){
			header('Location:' . PAGE_404); 
			exit();
		}
		else if ( $option == LOGIN){
		
			header('Location:' . LOGIN_PAGE); 
			exit();
		}
	
	}
	
	
?>