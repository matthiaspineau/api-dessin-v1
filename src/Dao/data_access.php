<?php

abstract class Data_Access {
  
	protected function dbConnect() {

		
		define("CONST_DB_HOST", "localhost");  // update with the location of your MySQL host.
		define("CONST_DB_USERNAME", "root");
		define("CONST_DB_PASSWORD", "");
		define("CONST_DB_SCHEMA", "api_starter");

		

		// establish a database connection
		if (!isset($GLOBALS['dbConnection'])) {
			$GLOBALS['dbConnection'] = new mysqli(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_SCHEMA);
		}

		// if an error occurred, record it
		if ($GLOBALS['dbConnection']->connect_errno) {
			// if an error occurred, raise it.
			$responseArray = App_Response::getResponse('500');
			$responseArray['message'] = 'MySQL error: ' . $GLOBALS['dbConnection']->connect_errno . ' ' . $GLOBALS['dbConnection']->connect_error;

		} else {
			// success
			$responseArray = App_Response::getResponse('200');
			$responseArray['message'] = 'Database connection successful.';
		}

		return $responseArray;

	}

	//--------------------------------------------------------------------------------------------------------------------
	protected function getResultSetArray($varQuery) {

		// attempt the query
        $rsData = $GLOBALS['dbConnection']->query($varQuery);

		if (isset($GLOBALS['dbConnection']->errno) && ($GLOBALS['dbConnection']->errno != 0)) {
			// if an error occurred, raise it.
			$responseArray = App_Response::getResponse('500');
			$responseArray['message'] = 'Internal server error. MySQL error: ' . $GLOBALS['dbConnection']->errno . ' ' . $GLOBALS['dbConnection']->error;
		} else {       
            // success
			$rowCount = $rsData->num_rows;
			
			if ($rowCount != 0) {
				// move result set to an associative array
                $rsArray = $rsData->fetch_all(MYSQLI_ASSOC);
			
				// add array to return
				$responseArray = App_Response::getResponse('200');
				$responseArray['data'] = $rsArray;
			
			} else {
				// no data returned
				$responseArray = App_Response::getResponse('204');
                $responseArray['message'] = 'Query did not return any results.';
			}
			
		}

		return $responseArray;
		
	}

	protected function setResultSetArray($varQuery) {

		// var_dump($GLOBALS['dbConnection']);
		// attempt the query
        $rsData = $GLOBALS['dbConnection']->query($varQuery);

		if (isset($GLOBALS['dbConnection']->errno) && ($GLOBALS['dbConnection']->errno != 0)) {
			// if an error occurred, raise it.
			$responseArray = App_Response::getResponse('500');
			$responseArray['message'] = 'Internal server error. MySQL error: ' . $GLOBALS['dbConnection']->errno . ' ' . $GLOBALS['dbConnection']->error;
		} else {
			       
            // success
			if ($rsData) {

			
				// add array to return
				$responseArray = App_Response::getResponse('200');

			
			} else {
				// no data returned
				$responseArray = App_Response::getResponse('204');
                $responseArray['message'] = 'Query did not return any results.';
			}
			
		}

		return $responseArray;
		
	}

}