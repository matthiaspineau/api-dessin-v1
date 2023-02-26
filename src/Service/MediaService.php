<?php
class MediaService {

    public function __construct() {
		
    }


    public function createMedia($params) {
        // var_dump($params);

        if (!isset($_FILES['file'])) {
            return array('success' => false, "desc" => 'pas de fichiers');
        }

        $files = $_FILES;
        $mediasCollection = array();

        for ($i = 0; $i < $params['nb_files']; $i++) {
            $media = array();
            $timestamp = time();
            $media['name'] = $timestamp; 
            $media['reference'] = $params['reference'];
            $media['title'] =  "";
            $media['ext'] =  "";
   
            $mediasCollection[] = $media;


            $currentFile = array(
                'name' => $files['file']['name'][$i],
                'tmp_name' => $files['file']['tmp_name'][$i],
                'error' => $files['file']['error'][$i],
                'size' => $files['file']['size'][$i],
                'type' => $files['file']['type'][$i],
            );

            $this->uploadFormatOriginal($currentFile, $media['name']);

        }
    }

    /**
     * crée et upload image dans le dossier uploads
     */
    public function uploadFormatOriginal(array $file, $mediaName) {
        // var_dump($file);
        // var_dump($mediaName);
        $pathUploads =  dirname(__FILE__, 3) . '/uploads2/' . 'original';
        $location =  $pathUploads.'/'.$mediaName;

        // file extension
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        // Valid extensions
        $valid_ext = array("jpg","png");
        
        $successUpload = 0;
        if(in_array($file_extension, $valid_ext)){
            // Upload file
            if(move_uploaded_file($file['tmp_name'],$location)){
                $successUpload = 1;
            } 
        }
    }

    public function updateMedia() {
        
    }

    public function deleteMedia() {
        
    }

    public function getMediaCollection() {

        $get = $_GET;

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

        $mediaDao = new MediaDao();
        $result = $mediaDao->getMediaCollectionDao($params);

        return $result;
    }


}