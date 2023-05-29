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
        $parameters = array();
        if (isset($params['id']) && $params['id'] > 0) {
            $parameters['id'] = $params['id'];
        }
        if (isset($params['search']) && strlen($params['search']) > 0) {
            $parameters['search'] = $params['search'];
        }
        if (isset($params['limit'])) {
            $parameters['limit'] =  $params['limit'];
        }
        if (isset($params['offset'])) {
            $parameters['offset'] = $params['offset'];
        }
        if (isset($params['order']) && strlen($params['order']) > 0) {
			$parameters['order'] = $params['order'];
		}
		if (isset($params['direction']) && strlen($params['direction']) > 0) {
			$parameters['direction'] = $params['direction'];
		}
        if (isset($params['is_indexed']) && strlen($params['is_indexed']) > 0) {
			$parameters['is_indexed'] = $params['is_indexed'];
		}
        $result = array();
        // var_dump($params);
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->getGroupMediaCollectionDao($parameters);

        return $result;
    }

    public function deleteGroupMedia($params) {
        $result = array();

        $mediaDao = new MediaGroupsDao();
        $result = $mediaDao->deleteGroupMediaDao($params);

        return $result;
    }
    
    public function updateMediasGroups($params) {

        $result = array();
        $mediaGroupsDao = new MediaGroupsDao();
        $result = $mediaGroupsDao->updateMediasGroupsDao($params);
        return $result;
    }
   

}