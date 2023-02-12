<?php 

class DrawingDao extends Data_Access {

	// protected $object_name = 'app_test';
	protected $object_view_test_article = 'test_article';
	protected $object_view_drawing_item = 'drawing_item';
	protected $object_view_drawing_category = 'drawing_category';
	protected $object_view_store_media = 'store_media';

	//----------------------------------------------------------------------------------------------------
	public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

	//----------------------------------------------------------------------------------------------------

	
	/**
	 * 
	 */
	public function saveDrawing($arrayParams) {

		// var_dump($arrayParams);

		$sqlInsertValue = array();
		foreach($arrayParams as $key => $draw) {
			$sqlInsertValue[] = sprintf("( '%s', '%s', '%s', %d, '%s')"
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $draw['new_drawing_name']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $draw['new_drawing_reference']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $draw['new_drawing_title'])
				, $draw['id_drawing_category']
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $draw['drawing_tags']) 
			);
		}
	
		$insertValue = implode(', ', $sqlInsertValue);


		$sql = sprintf("INSERT INTO %s "
					. " ( `drawing_name`, `drawing_reference`, `drawing_title`, `id_drawing_category`, `drawing_tags`) "
					. " VALUES "
					. " %s "
					, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
					, $insertValue
				);
		
		$result = $this->setResultSetArray($sql);
		// var_dump($result);
		if ($result['response'] !== '200') {
			$responseArray = App_Response::getResponse('403');
			$responseArray = array('success' => FALSE, 'response' => 502, 'responseDescription' => 'Dao : erreur lors de l ajout du fichier');
		} else {
			$responseArray = $result;
		} 
	
		return $responseArray;
	}

	/**
	 * 
	 */
	public function getDrawing($params) {

		// build the query
		$whereClause = array();
		$where = '';
		$whereClausePaginate = '';
		$order = '';

		if (isset($params['id']) && count($params['id']) > 0) {
			$whereClause[] = " id IN (" . implode(', ', $params['id']) . ")";
		}

		if (isset($params['search']) && strlen($params['search']) > 0) {
			$whereClause[] = " drawing_title LIKE '%" . $params['search'] . "%' ";
		}

		if (isset($params['limit']) && $params['limit'] > 0) {
			$whereClausePaginate .= " LIMIT " . $params['limit'] . " ";
		}

		if (isset($params['offset']) && $params['offset'] > 0) {
			$whereClausePaginate .= " OFFSET " . $params['offset'] . " ";
		}
		if (isset($params['order']) && strlen($params['order']) > 0) {
			$order .= " ORDER BY " . $params['order'] . " ";
		}
		if (isset($params['direction']) && strlen($params['direction']) > 0) {
			$order .= " " . $params['direction'] . " ";
		}
		

		if (!empty($whereClause)) {
			$where = " WHERE " . implode(' AND ', $whereClause);
		}



		// requete 1 for count
		$sqlForCount = sprintf("SELECT COUNT(*) FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
			, $where
		);
		$resultForCount = $this->getResultSetArray($sqlForCount);
		$countItem = intval(implode($resultForCount['data'][0]));
		// var_dump($countItem);
		// requete 2 for result final


		$sql = sprintf("SELECT * FROM  %s "
			. " %s  %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
			, $where
			, $whereClausePaginate
			, $order
		);
		// var_dump($sql);

		$result = $this->getResultSetArray($sql);
		$result['count'] = $countItem;
		// var_dump($result['count'] );
	
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
	public function deleteDraw($params) {

		$result = App_Response::getResponse('403');
		// DELETE FROM nom_de_table WHERE condition.
		// var_dump($params);

		if(isset($params['id'])) {
			$sql = sprintf("DELETE FROM %s "
					. " WHERE "
					. " id= %d "
					, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
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

	public function getCategoryDrawing() {
		
	}



}