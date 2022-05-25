<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];

	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
	
    // Access-Control headers are received during OPTIONS requests
    if ($request_method == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

	function getTacheParent($idType) {
		global $cnx;
		$sql = $cnx->prepare("select parent.nomType FROM type AS parent LEFT JOIN type AS child ON child.idparent = parent.idType WHERE child.idType = ?");
		$sql->bindValue(1, $idType);
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$tache = [
				'name' => $row['nomType'],
			];
			$response[] = $tache;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	switch($request_method)
	{
		case 'GET':
			getTacheParent($_GET['idType']);
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>