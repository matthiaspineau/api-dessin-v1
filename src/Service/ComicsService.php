<?php

class ComicsService {

    public function __construct() {
		
	}

    /**
     * id                        int
     * date                      date                   
     * reference                 string 
     * isActive                  int
     * 
     * in Json ----------------------------  
     * titre                     string
     * sous titre                string
     * description               string
     * array ids                 array
     * arrays ids order          array
     */

    public function addComics($params) {
        // var_dump($params);
        $ComicsDao = new ComicsDao();
        $result = $ComicsDao->addComics($params);
        
        return $result;

    }

    public function getComicsCollection() {

        $result = array();

        $params = array();

        $comicsDao = new ComicsDao();
        $result = $comicsDao->getComicsCollection($params);

        return $result;

    }


    

}