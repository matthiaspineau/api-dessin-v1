<?php
class MediaGroupsDao extends Data_Access {

    // protected $object_name = 'app_test';
    protected $object_view_media_groups = 'media_groups';

    public function __construct() {

        $res = $this->dbConnect();
        
        if ($res['response'] != '200') {
            echo "Houston? We have a problem.";
            die;
        }
	}

    public function addMediaGroupsDao($params) {

        $sql = sprintf("INSERT INTO %s "
        . " ( `reference`, `information`, `medias`) "
        . " VALUES "
        . " ('%s', '%s', '%s') "
        , CONST_DB_SCHEMA . "." . $this->object_view_media_groups
        , $params['reference']
        , json_encode(array("empty" => true))
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

    
	public function updateMediasOfGroupsDao($params) {

        // var_dump($params);

        $setClause = array();
        if (isset($params['medias']) && strlen($params['medias']) > 0) {
            $setClause[] = sprintf("`medias`= '%s'", $params['medias']);
        }
        if (isset($params['information']) && strlen($params['information']) > 0) {
            $setClause[] = sprintf("`information`= '%s'", $params['information']);
        }
        if (isset($params['is_active']) && is_numeric($params['is_active'])) {
            $setClause[] = sprintf("`is_active`= %d", $params['is_active']);
        }
        if (isset($params['reference']) && strlen($params['reference']) > 0) {
            $setClause[] = sprintf("`reference`= '%s'", $params['reference']);
        }
 
		$sql = sprintf("UPDATE %s "
			. " SET   %s "
			. " WHERE "
            . "  `id`= %d "
			, CONST_DB_SCHEMA . "." . $this->object_view_media_groups
            ,  implode(' , ', $setClause)
            ,  $params['id']
		);  

        var_dump($sql);
        $result = array();
        $result = $this->setResultSetArray($sql);

        if ( !$result['success'] ) {
            return App_Response::getResponse('403');
        }

        return $result;
	}
    
}