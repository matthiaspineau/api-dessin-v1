<?php
class MediaGroupsService {

    public function __construct() {
		
    }


    public function addMediaGroups($params) {
        $result = array();
        // var_dump($params);
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->addMediaGroupsDao($params);
        

        $result = array('success' => TRUE, 'response' => 200, 'responseDescription' => 'le fichier à bien été ajouter');
        return $result;
    }

    public function getGroupMedia($params) {
        // $get = $_GET;
        $get = $_POST;

        // var_dump($get['search']);

        if (isset($get['id']) && strlen($get['id']) > 0) {
            $params['id'] = $get['id'];
        }
        if (isset($get['search']) && strlen($get['search']) > 0) {
            $params['search'] = $get['search'];
        }
        if (isset($get['limit'])) {
            $params['limit'] =  intval($get['limit']);
        }
        if (isset($get['offset'])) {
            $params['offset'] =  intval($get['offset']);
        }
        if (isset($params['order']) && strlen($params['order']) > 0) {
			$params['order'] =  $get['order'];
		}
		if (isset($params['direction']) && strlen($params['direction']) > 0) {
			$params['direction'] =  $get['direction'];
		}


        $result = array();
        // var_dump($params);
        $mediaDao = new MediaDao();
        $result = $mediaDao->getGroupMediaDao($params);

        return $result;
    }

    
    public function updateMediasOfGroups($params) {

        var_dump($params);
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->updateMediasOfGroupsDao($params);
        return $result;
    }
   

}