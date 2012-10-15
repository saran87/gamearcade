<?php
/*
* ErrorHandler class - used to handle errors and log it to appropriate log file
*
* Static Handle Error method will handle the error and log it to a file
*
*/


class ErrorHandler{
	

	/*
	* Handle Error - Decides whether the error has to be  logged or not 
	*                based on the configuration in settings file.
	* @param error string 
	*/
	static public function HandleError($error){		
	
		if(IS_LOG_ENABLED){
			error_log(self::GetRequestDetail(),3,ERROR_LOG_FILE);
		}	
		redirectPage( $error);
	}
	
	static public function GetRequestDetail() {
        
		$startLog = "\n==========================================================================================================\n";
		$userDetail = "From : " . $_SERVER["SERVER_ADDR"] . "\n Requested URL: " . $_SERVER['REQUEST_URI'] . "\n Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n Access Time: " . $_SERVER[REQUEST_TIME] . "\n";
		$callStack = self::GetCallTrace();
		$endLog =  "\n==========================================================================================================\n";
		$logStr = $startLog . $userDetail . $callStack . $endLog;
		return $logStr;
    }
	
	 static function GetCallTrace() { 
        //stack the output buffer
		ob_start(); 
        debug_print_backtrace(); 
        $trace = ob_get_contents(); 
        ob_end_clean(); 

        // Remove first item from backtrace as it's this function which 
        // is redundant. 
        $trace = preg_replace ('/^#0\s+' . __FUNCTION__ . "[^\n]*\n/", '', $trace, 1); 

        // Renumber backtrace items. 
        $trace = preg_replace ('/^#(\d+)/me', '\'#\' . ($1 - 1)', $trace); 

        return $trace; 
    }
}


?>