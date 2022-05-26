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

	function getTaches() {
		global $cnx;
		$sql = $cnx->prepare("select idTache, nomTache, idType, idList from tache");
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$tache = [
				'id' => $row['idTache'],
				'name' => $row['nomTache'],
				'idtype' => $row['idType'],
				'idlist' => $row['idList'],
			];
			$response[] = $tache;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	function insert() {
		global $cnx;
		$json_str = file_get_contents('php://input');
		$tache = json_decode($json_str);

		$sql = $cnx->prepare("INSERT INTO tache (nomTache, idType, idList) VALUES (?, ?, ?)");
		$sql->bindValue(1, $tache->nom);
		$sql->bindValue(2, $tache->idType);
		$sql->bindValue(3, $tache->idlist);
		$sql->execute();

		echo json_decode(true);
	}
	
	function update()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$tache = json_decode($json_str);
		
		$sql= $cnx->prepare("UPDATE tache set nomTache = ?, idType = ?, idList = ? where idTache = ?");
		$sql->bindValue(1, $tache->nom);
		$sql->bindValue(2, $tache->idType);
		$sql->bindValue(3, $tache->idList);
		$sql->bindValue(4, $tache->id);
		$sql->execute();

		echo json_decode(true);
	}
	
	function delete($idTache) {
		global $cnx;
		
		$json_str = file_get_contents('php://input');
		$tache = json_decode($json_str);
		
		$sql = $cnx->prepare("DELETE FROM tache where idTache = ?");
		$sql->bindValue(1, $idTache);
		$sql->execute();

		echo json_decode(true);
	}

	switch($request_method)
	{
		case 'GET':
			getTaches();
			break;
		case 'POST':
			insert();
			break;
		case 'PUT':
			update();
			break;
		case 'DELETE':
			delete($_GET['id']);
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>