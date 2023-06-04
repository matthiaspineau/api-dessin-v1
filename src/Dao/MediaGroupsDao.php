<?php
class MediaGroupsDao extends Data_Access {

    protected $object_view_media_groups = 'media_groups';
    protected $res;

    public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

    public function getGroupMediaCollectionDao($params) {
        // var_dump($params);
        $result = array();
        $exec = array();
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

		$sql = sprintf("SELECT * FROM  %s "
        . " %s  %s "
        , CONST_DB_SCHEMA . "." . $this->object_view_media_groups
        , $where
        , $whereClausePaginate
        , $order
    );

        // requete count media pour la pagination
		$sqlCount = sprintf("SELECT COUNT(*) FROM  %s "
        . " %s "
        , CONST_DB_SCHEMA . "." . $this->object_view_media_groups
        , $where
        );
        $execCount = $this->getResultSetArray($sqlCount);
        $resultCount = intval(implode($execCount['data'][0]));

		$exec = $this->getResultSetArray($sql);
        $exec['count'] = $resultCount;

		if ($exec['response'] !== '200') {
			$result = App_Response::getResponse('403');
		} else {
			$result = $exec;
		}
        // var_dump($result);
        if (isset($params['is_indexed']) && $params['is_indexed'] == 1) {
            $arrayDataIndexed = array();
            foreach($result['data'] as $row) {
                $arrayDataIndexed[$row['id']] = $row;
            }
            // var_dump($arrayData);
            $result['data'] = $arrayDataIndexed;
        }
        
    //    var_dump($result);
		return $result;
    }


    public function addMediaGroupsDao($params) {

        $result = array();
        $exec = array();

        $sql = sprintf("INSERT INTO %s "
        . " ( `reference`, `information`, `ids_medias`, `icone`) "
        . " VALUES "
        . " ('%s', '%s', '%s', '%s') "
        , CONST_DB_SCHEMA . "." . $this->object_view_media_groups
        , $params['reference']
        , json_encode(array("empty" => true))
        , json_encode(array("ids_medias" => array()))
        , $params['icone'] ?? ''
        );

        $exec = $this->setResultSetArray($sql);

        if ($exec['response'] !== '200' || $exec['success'] == false) {
            $result = array('success' => FALSE, 'response' => 502, 'responseDescription' => 'Dao : erreur lors de l ajout du group');
        } else {
            $result = $exec;
        } 

        return $result;
    }

    public function deleteGroupMediaDao($params) {

        $result = array();
        $exec = array();

		if (isset($params['id']) && is_numeric($params['id'])) {
			$sql = sprintf("DELETE FROM %s "
					. " WHERE "
					. " id= %d "
					, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
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

    
	public function updateMediasGroupsDao($params) {

        $result = array();
        $exec = array();

        $setClause = array();
        if (isset($params['ids_medias']) && strlen($params['ids_medias']) > 0) {
            $setClause[] = sprintf("`ids_medias`= '%s'", $params['ids_medias']);
        }
        if (isset($params['information']) && strlen($params['information']) > 0) {
            $setClause[] = sprintf("`information`= '%s'", addslashes($params['information']));
        }
        if (isset($params['is_active']) && is_numeric($params['is_active'])) {
            $setClause[] = sprintf("`is_active`= %d", $params['is_active']);
        }
        if (isset($params['reference']) && strlen($params['reference']) > 0) {
            $setClause[] = sprintf("`reference`= '%s'", $params['reference']);
        }
        if (isset($params['icone']) && strlen($params['icone']) > 0) {
            $setClause[] = sprintf("`icone`= '%s'", $params['icone']);
        }

		$sql = sprintf("UPDATE %s "
			. " SET   %s "
			. " WHERE "
            . "  `id`= %d "
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
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
    
}