<?php
class MediaService {

    public function __construct() {
		
    }


    public function addGroupMedia($params) {
        $result = array();
        var_dump($params);
        $mediaDao = new MediaDao();
        $result = $mediaDao->addGroupMedia($params);
        

        $result = array('success' => TRUE, 'response' => 200, 'responseDescription' => 'le fichier à bien été ajouter');
        return $result;
    }

    public function updateMediasOfGroups($params) {

        var_dump($params);
        $mediaDao = new MediaDao();
        $result = $mediaDao->updateMediasOfGroups($params);
        return $result;
    }

    public function getGroupMedia($params) {
        $get = $_GET;
        // var_dump($get['search']);
        $params = array();
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

        $drawingDao = new DrawingDao();
        $result = $drawingDao->getDrawing($params);

        return $result;
    }

    public function updateGroupMedia($params) {
        
        var_dump($params);
        $mediaDao = new MediaDao();
        $result = $mediaDao->updateGroupMedia($params);
    }

}