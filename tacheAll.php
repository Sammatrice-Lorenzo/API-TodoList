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

	function getTacheAll() {
		global $cnx;
		$sql = $cnx->prepare("SELECT tache.idTache, enfant.nomType AS 'nomType', parent.nomType AS 'nomTypeParent' from Type AS Parent LEFT OUTER JOIN Type AS Enfant ON enfant.idParent = parent.idType INNER JOIN tache ON tache.idType = enfant.idtype");
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$tache = [
				'id' => $row['idTache'],
				'name' => $row['nomType'],
				'nameParent' => $row['nomTypeParent'],
			];
			$response[] = $tache;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	switch($request_method)
	{
		case 'GET':
			getTacheAll();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>