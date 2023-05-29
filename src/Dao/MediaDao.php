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

	public function getMedias($params) {

		$whereClause = array();
		$where = '';

		if (isset($params['ids']) && count($params['ids']) > 0) {
			$whereClause[] = " id IN (" . implode(', ', $params['ids']) . ")";
		}

		if (!empty($whereClause)) {
			$where = " WHERE " . implode(' AND ', $whereClause);
		}

		$sql = sprintf("SELECT * FROM  %s "
			. " %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_media
			, $where
	
		);

		$exec = $this->getResultSetArray($sql);

		if ($exec['response'] !== '200') {
			$result = App_Response::getResponse('403');
		} else {
			$result = $exec;
		}
		return $result;
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
  
		$return = array();
		$values = array();
		foreach($params as $key => $media) {
			$values[] = sprintf("( '%s', '%s', '%s', '%s', '%s')"
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['original_name']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['name']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['reference']) 
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['title'])
				, mysqli_real_escape_string($GLOBALS['dbConnection'], $media['ext'])

			);
		}

		$sql = sprintf("INSERT INTO %s "
                . " ( `original_name`, `name`, `reference`, `title`, `ext`) "
                . " VALUES "
                . " %s "
                , CONST_DB_SCHEMA . "." . $this->object_view_media
                , implode(', ', $values)
            );

		
		$result = $this->setResultSetArray($sql);

		if ($result['response'] !== '200') {

			$return = array(
                'success' => false, 
                'response' => 403,
                'responseDescription' => 'Dao : erreur lors de l ajout du fichier');
		} else {
			$return = array(
                'success' => true, 
                'desc' => 'Les media ont bien été ajouter');
		} 
	
		return $return;
    }


    public function updateMediaDao($params) {
        $result = array();
        $exec = array();

        $setClause = array();

        if (isset($params['reference']) && is_numeric($params['reference'])) {
            $setClause[] = sprintf("`reference`= '%s'", $params['reference']);
        }
        if (isset($params['title']) && strlen($params['title']) > 0) {
            $setClause[] = sprintf("`title`= '%s'", $params['title']);
        }
 
		$sql = sprintf("UPDATE %s "
			. " SET   %s "
			. " WHERE "
            . "  `id`= %d "
			, CONST_DB_SCHEMA . "." . $this->object_view_media
            ,  implode(' , ', $setClause)
            ,  $params['id']
		);  

        $exec = $this->setResultSetArray($sql);

        if ( !$exec['success'] ) {
            return App_Response::getResponse('403');
        }

        $result = $exec;

        return $result;
    }

    public function deleteMediaDao($params) {

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
		// var_dump($exec);
		if ($exec['response'] !== '200') {
			$result = App_Response::getResponse('403');
		} else {
			$result = $exec;
		}
		return $result;
    }



	public function getMediaDao($params) {

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
			, CONST_DB_SCHEMA . "." . $this->object_view_media
			, $where
		);
		$resultForCount = $this->getResultSetArray($sqlForCount);
		$countItem = intval(implode($resultForCount['data'][0]));
		// var_dump($countItem);
		// requete 2 for result final


		$sql = sprintf("SELECT * FROM  %s "
			. " %s  %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_media
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

}