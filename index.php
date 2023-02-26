<?php

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
// header('Content-Type: application/json');
header("Content-Type: text/html; charset=utf-8");


require_once ('./src/app_autoloader.php');

//----------------------------------------------------------------------------------------
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestController = '';
$requestAction = '';
$requestParams = '';
// var_dump($_SERVER['REQUEST_METHOD']);
// var_dump($_GET);
// var_dump($_FILES);
// var_dump(json_decode(file_get_contents('php://input'), true));

if (in_array($requestMethod, ["GET", "POST", "PUT", "DELETE", "OPTIONS"])) {

	if ($requestMethod == "OPTIONS") {
		return;
	}

	if ($requestMethod == "GET") {
		$get = $_GET;
		// $q = $_GET['q'];
		// $requestController = $q;
		// $requestParams = '';
		// var_dump($get);
		if (isset($get['controller'])) {
			$requestController = $get['controller'];
		}
		if (isset($get['action'])) {
			$requestAction = $get['action'];
		}
		if (isset($get['params'])) {
			$requestParams = $get['params'];
		}

	} 

	if ($requestMethod == "POST") {

		$post = $_POST;
		if (empty($post)) {
			$post = json_decode(file_get_contents('php://input'), true);
		} 
		// var_dump($post);

		if (isset($post['controller'])) {
			$requestController = $post['controller'];
		}
		if (isset($post['action'])) {
			$requestAction = $post['action'];
		}
		if (isset($post['params'])) {
			$requestParams = $post['params'];
		}
	
		// var_dump($requestParams);
		if (isset($requestParams) && $requestParams != '') {
			$requestParams = json_decode($requestParams, true);
		}

		// var_dump($requestParams);
	
	}

	// var_dump($requestController);
	// var_dump($requestAction);
	// var_dump($requestParams);
	$cApiHandler = new API_Handler();

	$result = $cApiHandler->execCommand($requestController, $requestAction, $requestParams);

	$returnArray = json_encode($result);

	// var_dump($returnArray);
	echo $returnArray;


	if (isset($cApiHandler)) {unset($cApiHandler);}

} else {
	// $success = TRUE;
	// $response = '200';
	// $responseDescription = 'The request has succeeded'
	
	// $returnArray = array('success' => $success, 'response' => $response, 'responseDescription' => $responseDescription);
	$returnArray = App_Response::getResponse('405');
	echo(json_encode($returnArray));
}