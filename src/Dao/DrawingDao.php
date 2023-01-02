<?php 

class DrawingDao extends Data_Access {

	// protected $object_name = 'app_test';
	protected $object_view_test_article = 'test_article';
	protected $object_view_drawing_item = 'drawing_item';
	protected $object_view_drawing_category = 'drawing_category';

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

		$sql = sprintf("INSERT INTO %s "
					. " ( `drawing_name`, `drawing_title`, `id_drawing_category`, `drawing_tags`) "
					. " VALUES "
					. " ( '%s', '%s', %d, '%s') "
					, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
					, mysqli_real_escape_string($GLOBALS['dbConnection'], $arrayParams['new_drawing_name']) 
					, mysqli_real_escape_string($GLOBALS['dbConnection'], $arrayParams['new_drawing_title']) 
					, $arrayParams['id_drawing_category']
					, mysqli_real_escape_string($GLOBALS['dbConnection'], $arrayParams['drawing_tags']) 
				);

		$result = $this->setResultSetArray($sql);

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

		if (isset($params['id']) && count($params['id']) > 0) {
			$whereClause[] = " id IN (" . implode(', ', $params['id']) . ")";
		}

		// $paramsa['tags'] = 'dessin';
		
		// if (true) {
		// 	$whereClause[] = " drawing_tags LIKE (" . $paramsa['tags'] . ")";
		// }
		//             SELECT * FROM Customers
		// WHERE CustomerName LIKE '%or%';

		if (!empty($whereClause)) {
			$where = " WHERE " . implode(' AND ', $whereClause);
		}
		$sql = sprintf("SELECT * FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_drawing_item
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