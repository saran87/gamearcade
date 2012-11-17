<?php

	/**
	 * The score.php is where all the request for score creation and score turns 
	 * are handled and dispatched to appropriate methods .
	 *
	 *
	 */
	 
	 // ------------------------------------------------------------------------

	/**
	 * Score Class
	 *
	 * This class contains functions that handles and process the request 
	 * related to score play 
	 *
	 * @author	Saravana Kumar
	 */
	 
	class Score extends BaseController{
	
		private $title = "Game Arcade";
		
		function __construct(){
	
			require_once(ROOT_PATH . "/dataAccess/scoreBoardAccess.php");
		
		}
		
		public function index(){
		
			$data = array();
			if($this->authenticate()){
				$scoreBoard = new ScoreBoardAccess();
				$data["data"] = $scoreBoard->getDetails();
			}
			else{
				$data['error']['isLoginRequired'] = true;
			}
			ouputJson($data);
		}
	}
	
?>