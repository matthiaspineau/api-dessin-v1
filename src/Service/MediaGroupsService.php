<?php
class MediaGroupsService {

    public function __construct() {

    }

    public function addMediaGroups($params) {

        $result = array();
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->addMediaGroupsDao($params);
        

        $result = array('success' => TRUE, 'response' => 200, 'responseDescription' => 'le fichier à bien été ajouter');
        return $result;
    }

    public function getGroupMediaCollection($params) {
        // $get = $_GET;
        $reqParams = array();
        $reqParams = $params;
        $parameters = array();


        // if (isset($_GET) && !empty($_GET)) {
        //     $reqParams = $_GET;
        // }
        // if (isset($_POST)  && !empty($_POST)) {
        //     $reqParams = $_POST;
        // }
    
        // var_dump($reqParams);
        // var_dump(isset($reqParams['offset']));

        if (isset($reqParams['id']) && $reqParams['id'] > 0) {
            $parameters['id'] = $reqParams['id'];
        }
        if (isset($reqParams['search']) && strlen($reqParams['search']) > 0) {
            $parameters['search'] = $reqParams['search'];
        }
        if (isset($reqParams['limit'])) {
            $parameters['limit'] =  $reqParams['limit'];
        }
        if (isset($reqParams['offset'])) {
            $parameters['offset'] =  $reqParams['offset'];
        }
        if (isset($reqParams['order']) && strlen($reqParams['order']) > 0) {
			$parameters['order'] =  $reqParams['order'];
		}
		if (isset($reqParams['direction']) && strlen($reqParams['direction']) > 0) {
			$parameters['direction'] =  $reqParams['direction'];
		}


        $result = array();
        // var_dump($params);
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->getGroupMediaCollectionDao($parameters);

        return $result;
    }
    
    public function updateMediasGroups($params) {

        $result = array();
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->updateMediasGroupsDao($params);
        return $result;
    }
   

}