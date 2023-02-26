<?php
class MediaDao extends Data_Access {

    protected $object_view_media = 'media';

    public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

    public function getMediaCollectionDao($params) {
 
        $exec = array();
        $execCount = array();
        $result = array();
        $resultCount = 0;
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

		// requete count media pour la pagination
		$sqlCount = sprintf("SELECT COUNT(*) FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_media
			, $where
		);
		$execCount = $this->getResultSetArray($sqlCount);
		$resultCount = intval(implode($execCount['data'][0]));


		$sql = sprintf("SELECT * FROM  %s "
			. " %s  %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_media
			, $where
			, $whereClausePaginate
			, $order
		);

		$exec = $this->getResultSetArray($sql);
		$exec['count'] = $resultCount;
	
		if ($exec['response'] !== '200') {
			$result = App_Response::getResponse('403');
		} else {
			$result = $exec;
		}
		return $result;
    }

    public function createMediaDao($params) {
 
		$values = array();
		foreach($params as $key => $media) {
			$values[] = sprintf("( '%s', '%s', '%s', %d, '%s')"
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['new_drawing_name']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['new_drawing_reference']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['new_drawing_title'])
				, $media['id_drawing_category']
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['drawing_tags']) 
			);
		}

		$sql = sprintf("INSERT INTO %s "
                . " ( `drawing_name`, `drawing_reference`, `drawing_title`, `id_drawing_category`, `drawing_tags`) "
                . " VALUES "
                . " %s "
                , CONST_DB_SCHEMA . "." . $this->object_view_media
                , implode(', ', $values)
            );
		
		$result = $this->setResultSetArray($sql);

		if ($result['response'] !== '200') {

			$responseArray = array(
                'success' => false, 
                'response' => 403,
                'responseDescription' => 'Dao : erreur lors de l ajout du fichier');
		} else {
			$responseArray = $result;
		} 
	
		return $responseArray;
    }

    public function updateMediaDao() {
        
    }

    public function deleteMediaDao() {

        $result = array();
        $exec = array();

		if (isset($params['id'])) {
			$sql = sprintf("DELETE FROM %s "
					. " WHERE "
					. " id= %d "
					, CONST_DB_SCHEMA . "." . $this->object_view_media
					, $params['id']
				);

			$exec = $this->setResultSetArray($sql);
		}

		if ($exec['response'] !== '200') {
			$result = App_Response::getResponse('403');
		} else {
			$result = $exec;
		}
		return $result;
    }

    
  
}