<?php

// Build the class mapping array
$mapping = [
   
    // app classes
    'API_Handler' => './src/api_handler.php',
    'App_Response' => './src/app_response.php',
    // 'JWT' => './src/app_jwt.php',

    // database classes
    'Data_Access' => './src/Dao/data_access.php',
    'App_API_Key' => './src/Dao/app_api_key.php',
    'App_Test' => './src/Dao/app_test.php',
    
    // API Drawing
    'DrawingService' => './src/Service/DrawingService.php',
    'DrawingDao' => './src/Dao/DrawingDao.php',
    // API Comics
    'ComicsService' => './src/Service/ComicsService.php',
    'ComicsDao' => './src/Dao/ComicsDao.php',
    // API Media
    'MediaService' => './src/Service/MediaService.php',
    'MediaDao' => './src/Dao/MediaDao.php',
];

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require_once $mapping[$class];
    }
}, true);