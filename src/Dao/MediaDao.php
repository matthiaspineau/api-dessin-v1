<?php
class MediaDao extends Data_Access {

    // protected $object_name = 'app_test';
    protected $object_view_media_groups = 'media_groups';

    public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

    public function getGroupMedia() {
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
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
			, $where
		);
		$resultForCount = $this->getResultSetArray($sqlForCount);
		$countItem = intval(implode($resultForCount['data'][0]));
		// var_dump($countItem);
		// requete 2 for result final


		$sql = sprintf("SELECT * FROM  %s "
			. " %s  %s "
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
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

    public function addGroupMedia($params) {
       

        $sql = sprintf("INSERT INTO %s "
            . " ( `reference`, `information`) "
            . " VALUES "
            . " ('%s', '%s') "
            , CONST_DB_SCHEMA . "." . $this->object_view_media_groups
            , $params['reference']
            , json_encode(array("empty" => true))

        );
        var_dump($sql);
        $result = $this->setResultSetArray($sql);
        var_dump($result);
        if ($result['response'] !== '200' || $result['success'] == false) {
			$responseArray = App_Response::getResponse('403');
			$responseArray = array('success' => FALSE, 'response' => 502, 'responseDescription' => 'Dao : erreur lors de l ajout du group');
		} else {
			$responseArray = $result;
		} 
	
		return $responseArray;

    }

	public function updateMediasOfGroups($params) {
		$whereClause = array();
        $newValue = array();

        var_dump($params);
		if (isset($params['id'])) {
			$whereClause[] = " id= ". intval($params['id']) ;
		}

		$sql = sprintf("UPDATE %s "
			. " SET %s "
            . ' %s '
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
            , " 'medias'= '" . json_encode($params['medias']). "'"
            , " WHERE " . implode(' AND ', $whereClause)
		);
        var_dump($sql);
        $result = array();
        $result = $this->getResultSetArray($sql);

        var_dump($result);
        if ( !$result['success'] ) {
            return App_Response::getResponse('403');
        }



        return $result;
	}

    public function updateGroupMedia($params) {

        $whereClause = array();
        $newValue = array();

        var_dump($params);
		if (isset($params['id'])) {
			$whereClause[] = " id= ". intval($params['id']) ;
		}

    



       


        // UPDATE table
        // SET colonne_1 = 'valeur 1', colonne_2 = 'valeur 2', colonne_3 = 'valeur 3'
        // WHERE condition

        // UPDATE `media_groups` SET `media_tmp` = '{\"empty\": true}' WHERE `media_groups`.`id` = 3;

        $sql = sprintf("UPDATE %s "
			. " SET %s "
            . ' %s '
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
            , " media_tmp= '" . json_encode(array("a" => "oui")). "'"
            , " WHERE " . implode(' AND ', $whereClause)
		);
        var_dump($sql);
        $result = array();
        $result = $this->getResultSetArray($sql);

        var_dump($result);
        if ( !$result['success'] ) {
            return App_Response::getResponse('403');
        }



        return $result;
    }



    public function a() {
        // $whereClause = array();
        // var_dump($params);
		// if (isset($params['id'])) {
		// 	$whereClause[] = " id = ". intval($params['id']) ;
		// }

        // // get groups
        // $sql = sprintf("SELECT * FROM  %s "
		// 	. " %s "
		// 	, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
		// 	, " WHERE " . implode(' AND ', $whereClause)
		// );
        

        // $result = array();
        // $result = $this->getResultSetArray($sql);

        // if ( !$result['success'] ) {
        //     return App_Response::getResponse('403');
        // }

        // $groupsMedia = $result['data'][0];
        // $information = json_decode($groupsMedia['information'], true);
        // var_dump($information);
    }

    
}