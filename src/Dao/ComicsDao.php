<?php 

class ComicsDao extends Data_Access {

	protected $object_view_comics = 'comics';

	//----------------------------------------------------------------------------------------------------
	public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

	//----------------------------------------------------------------------------------------------------

	public function getComicsCollection($params) {
		// build the query
		$whereClause = array();
		$where = '';

		if (isset($params['id']) && count($params['id']) > 0) {
			$whereClause[] = " id IN (" . implode(', ', $params['id']) . ")";
		}

		if (!empty($whereClause)) {
			$where = " WHERE " . implode(' AND ', $whereClause);
		}

		$sql = sprintf("SELECT * FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_comics
			, $where
		);

		$result = $this->getResultSetArray($sql);
	
		if ($result['response'] !== '200') {
			$responseArray = App_Response::getResponse('403');
		} else {
			$responseArray = $result;
		}
		return $responseArray;
	}
	
	/**
	 * 
	 */
	public function addComics($arrayParams) {

		$comics = $arrayParams;

		$sql = sprintf("INSERT INTO %s "
					. " ( `reference`, `information`) "
					. " VALUES "
					. " ( '%s', '%s') "
					, CONST_DB_SCHEMA . "." . $this->object_view_comics
					, mysqli_real_escape_string($GLOBALS['dbConnection'], $comics['reference'])
					, $comics['information']
				);

		$result = $this->setResultSetArray($sql);
var_dump($sql);
		if ($result['response'] !== '200') {
			$responseArray = App_Response::getResponse('403');
			$responseArray = array('success' => FALSE, 'response' => 502, 'responseDescription' => 'Dao : erreur lors de l ajout du comics');
		} else {
			$responseArray = $result;
		} 
		var_dump($result);
		return $responseArray;
	}

	/**
	 * 
	 */
	public function getComics($params) {

		// build the query
		$whereClause = array();
		$where = '';

		if (isset($params['id']) && count($params['id']) > 0) {
			$whereClause[] = " id IN (" . implode(', ', $params['id']) . ")";
		}

		if (!empty($whereClause)) {
			$where = " WHERE " . implode(' AND ', $whereClause);
		}
		$sql = sprintf("SELECT * FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_comics
			, $where
		);

		$result = $this->getResultSetArray($sql);
	
		if ($result['response'] !== '200') {
			$responseArray = App_Response::getResponse('403');
		} else {
			$responseArray = $result;
		}
		return $responseArray;
	}

	/**
	 * 
	 */
	public function deleteComics($params) {

		$result = App_Response::getResponse('403');
		// DELETE FROM nom_de_table WHERE condition.
		// var_dump($params);

		if(isset($params['id'])) {
			$sql = sprintf("DELETE FROM %s "
					. " WHERE "
					. " id= %d "
					, CONST_DB_SCHEMA . "." . $this->object_view_comics
					, $params['id']
				);

			$result = $this->setResultSetArray($sql);
		}

		if ($result['response'] !== '200') {
			$responseArray = App_Response::getResponse('403');
		} else {
			$responseArray = $result;
		}
		return $responseArray;
	}


}