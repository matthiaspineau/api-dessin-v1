<?php

class DrawingService {

    public function __construct() {
		
    }

    public function addStoreMedia($params) {

        $result = array();
        var_dump($params);
        $drawingDao = new DrawingDao();
        $result = $drawingDao->addStoreMedia($params);
        

        $result = array('success' => TRUE, 'response' => 200, 'responseDescription' => 'le fichier à bien été ajouter');
        return $result;
    }

    public function getStoreMedia($params) {
        var_dump($params);
        $result = array();

        $drawingDao = new DrawingDao();
        $result = $drawingDao->getStoreMedia($params);

        return $result;

    }

    /**
     * 
     */
    public function getDrawsCollection() {
        
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


  

    public function getDraw($params) {

        $result = array();

        $drawingDao = new DrawingDao();
        $result = $drawingDao->getDrawing($params);

        return $result;
    }

    /**
     * upload image : 
     *  - enregistre en bdd
     *  - upload image au format different
     *  - upload image original
     */
    public function uploadImage($params) {

        $result = array();
// var_dump($params);
        if (!isset($_FILES['file'])) {
            $result['response'] == 400;
        } 

        $files = $_FILES;
        $arrayDraw = array();
        
        for ($i = 0; $i < $params['nb_files']; $i++) {
            
            $timestamp = time();
            $params['time'] = $timestamp; 
            $params['new_drawing_name'] = $timestamp . '_' . $i . '_' . $files['file']['name'][$i]; 
            $params['new_drawing_title'] =  $params['drawing_title'] . ' ' . $i ;
            $params['new_drawing_reference'] =  $params['drawing_reference'];
            $arrayDraw[] = $params;
           
            // ------ upload images in folder
            $newFile = array(
                'name' => $files['file']['name'][$i],
                'tmp_name' => $files['file']['tmp_name'][$i],
                'error' => $files['file']['error'][$i],
                'size' => $files['file']['size'][$i],
                'type' => $files['file']['type'][$i],
                'new_name' => $timestamp . '_' . $i . '_' . $files['file']['name'][$i],
            );

            // $arrayFormatName = array('large', 'thumbnail');
            // $arrayFormatName = array('large', 'medium', 'small', 'thumbnail');
            $arrayFormatName = array( 'small', 'thumbnail');
            
            foreach($arrayFormatName as $value) {
                $this->imageOtherSizeUpload($newFile, $value);
            }
            $this->imageOriginalUpload($newFile);
            // ------ fin upload images in folder ------
    
        }
   
        // ----- save in bdd
        $drawingDao = new DrawingDao();
        $result = $drawingDao->saveDrawing($arrayDraw);
        

        $result = array('success' => TRUE, 'response' => 200, 'responseDescription' => 'le fichier à bien été ajouter');
        return $result;
    }

    /**
     * crée et upload les images au format différent dans le dossier uploads
     */
    public function imageOtherSizeUpload(array $file, string $formatSize) {
        $width = 0;
        $height = 0;
        $filename = $file['new_name'];
      
        switch ($formatSize) {
            case 'large':
                $width = 1024;
                // $height = 1024;
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'large';
            break;
            case 'medium':
                $width = 768;
                // $height = 768;
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'medium';
                break;
            case 'small':
                $width = 360;
                // $height = 360;
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'small';
                break;
            case 'thumbnail':
                $width = 150;
                // $height = 150;
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'thumbnail';
                break;
                        
            default:
                $width = 1024;
                // $height = 1024;
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'large';
                break;
        }

        $location =  $pathUploads.'/'.$filename;

        list($width_orig, $height_orig) = getimagesize($file['tmp_name']);
        $height = ($height_orig / $width_orig) * $width;

        $src = imagecreatefrompng($file['tmp_name']);
        $dst = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        imagepng($dst, $location,9);
       
        // $src = imagecreatefrompng($file['tmp_name']);
        // $newImage = imagecreate( $width, $height);
        // imagecopyresampled($newImage, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // imagepng($newImage, $location, 0); 
        // imagedestroy($src);
        // imagedestroy($newImage);
  

        // -----------------
        // $newwidth = $maxImgWidth;
        // $newheight = ($height / $width) * $newwidth;
        // $newImage = imagecreate($newwidth, $newheight);
        // imagecopyresampled($newImage, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // imagepng($newImage, "wiki2.png", 5); 
        // imagedestroy($src);
        // imagedestroy($newImage);
        // $resizedFlag = true;
        // -------------------


        // imagepng($dst, $location,9);
        //     imagedestroy($location);
        // imagedestroy($dst);
    }
 
    /**
     * crée et upload image dans le dossier uploads
     */
    public function imageOriginalUpload(array $file) {

        $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'original';

        $filename = $file['new_name'];

        $location =  $pathUploads.'/'.$filename;
        // file extension
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        // Valid extensions
        $valid_ext = array("jpg","png");
        
        $successUpload = 0;
        if(in_array($file_extension,$valid_ext)){
            // Upload file
            if(move_uploaded_file($file['tmp_name'],$location)){
                $successUpload = 1;
                
            } 
        }
    }

    public function deleteDraw($params) {

        $result = array();

        $drawingDao = new DrawingDao();
        $result = $drawingDao->deleteDraw($params);

		if ($result['response'] !== '200') {
            return $result;
		} else {
			$filename = $params['name'];

            $arrayFormatName = array('original', 'large', 'medium', 'small', 'thumbnail');
            
            foreach($arrayFormatName as $value) {
                $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . $value;
                $location =  $pathUploads.'/'.$filename;
                
                if (file_exists($location)) {
                    unlink($location);
                }
            }
           
		}

        return $result;
    }

}