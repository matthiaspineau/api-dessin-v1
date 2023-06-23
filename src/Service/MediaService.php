<?php
class MediaService {

    public function __construct() {
		
    }

    
    public function getMedias($params) {
        $result = array();
        $mediaDao = new MediaDao();
        $result = $mediaDao->getMedias($params);

        if (isset($params['indexed']) && $params['indexed'] == true) {
            if ($result['success'] == true) {
                $tmpArrayMedias = $this->formatIndexedListMedias($params['ids'], $result['data']);

                $result['data'] = $tmpArrayMedias;
            }
        }

        return $result;
    }

    public function formatIndexedListMedias(array $idsOrder, array $medias) {
        $tmpArray = array();
        if (!empty($idsOrder) && !empty($medias)) {

            foreach ($idsOrder as $index => $idOrder) {
                foreach ($medias as $k => $media) {
                    if ($media['id'] == $idOrder) {
                        $tmpArray[$index] = $media;
                    }
                }
            }
        }


        return $tmpArray;
    }

    public function getMediaCollectionGrid($params) {

        $result = array();

        $mediaDao = new MediaDao();
        $result = $mediaDao->getMediaCollectionGridDao($params);

        return $result;
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

    public function createMedia($params) {

        $errors = array();

        if (!isset($_FILES['file'])) {
            return array('success' => false, "desc" => 'pas de fichiers');
        }

        $files = $_FILES;
        $mediasCollection = array();

        for ($i = 0; $i < $params['nb_files']; $i++) {
            $media = array();
            $timestamp = time() . '_' . $i;
            $ext = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);
            $media['ext'] = $ext;
            $media['original_name'] = $_FILES['file']['name'][$i]; 
            $media['name'] = $timestamp . '.' . $ext; 
            $media['reference'] = $params['reference'];
            $media['title'] =  "";
   
            $mediasCollection[] = $media;

            $currentFile = array(
                'name' => $files['file']['name'][$i],
                'tmp_name' => $files['file']['tmp_name'][$i],
                'error' => $files['file']['error'][$i],
                'size' => $files['file']['size'][$i],
                'type' => $files['file']['type'][$i],
            );

            // controler , verifie que le $currentFile['tmp_name'] existe // sinon error
            // pas faire d'enregsitrement en bdd pour tout l'upload
            // et supprimer tous les fichier cree
            if (  strlen($currentFile['tmp_name']) < 1 || strlen($currentFile['size']) < 1 ) {
                $errors[] = $mediasCollection['original_name'] ?? '';
                foreach($mediasCollection as $value) {
                    $this->deleteFileMedia($value);
                }
                break;
            }

            $formatSize = array('large', 'medium', 'small', 'thumbnail');
            foreach($formatSize as $format) {
                $this->imageOtherSizeUpload($currentFile, $media['name'], $format);
            }
            
            $this->uploadFormatOriginal($currentFile, $media['name']);

        }

        // ----- save in bdd
        if (count($errors) > 0) {
            $result['desc'] = 'une erreur est survenue';
            $result['log'] = json_encode($errors);
        } else {
            $mediaDao = new MediaDao();
            $result = $mediaDao->createMediaDao($mediasCollection);
        }

        return $result;
    }

    /**
     * crée et upload les images au format différent dans le dossier uploads
     */
    public function imageOtherSizeUpload(array $file, $mediaName, $formatSize) {
        $width = 0;
        $height = 0;
        $filename = $mediaName;
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
        // imagepng($dst, $location, 9 );
        imagejpeg($dst, $location, -1);    
       
        imagedestroy($dst);
    }
 

    /**
     * crée et upload image dans le dossier uploads
     */
    public function uploadFormatOriginal(array $file, $mediaName) {

        $pathUploads =  dirname(__FILE__, 3) . '/uploads/' . 'original';
        $location =  $pathUploads.'/'.$mediaName;
        // var_dump($location);
        // file extension
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        // Valid extensions
        // $valid_ext = array("jpg","png");
   
        move_uploaded_file($file['tmp_name'], $location);

    }

    public function updateMedia($params) {

        $result = array();
        $mediaDao = new MediaDao();
        $result = $mediaDao->updateMediaDao($params);
        return $result;
        
    }

    public function deleteFileMedia($params) {
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

    public function deleteMedia($params) {
        $result = array();

        $mediaDao = new MediaDao();
        $result = $mediaDao->deleteMediaDao($params);

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