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
	
	function getLists() {
		global $cnx;
		$sql = $cnx->prepare("select idList, nomList from list");
		$sql->execute();
		$response = [];
		
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$list = [
				'id' => $row['idList'],
				'name' => $row['nomList'],
			];
			$response[] = $list;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	function insert() {
		global $cnx;
		$json_str = file_get_contents('php://input');
		$list = json_decode($json_str);

		$sql = $cnx->prepare("INSERT INTO list (nomList) VALUES (?)");
		$sql->bindValue(1, $list->nom);
		$sql->execute();
	}
	
	function update()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$list = json_decode($json_str);
		var_dump($list);

		$sql= $cnx->prepare("UPDATE list set nomList = ? where idList = ?");
		$sql->bindValue(1, $list->nom);
		$sql->bindValue(2, $list->idList);
		$sql->execute();
	}
	
	function delete($idList) {
		global $cnx;
		$var = parse_str(file_get_contents('php://input'), $_DELETE);

		$sql = $cnx->prepare("SELECT * from tache where idList = ?");
		$sql->bindValue(1, $idList);
		$sql->execute();

		if(sizeof($sql->fetchAll()) > 0) {
			$sqlTaches = $cnx->prepare("DELETE FROM tache where idList = ?");
			$sqlTaches->bindValue(1, $idList);
			$sqlTaches->execute();
		}
		
		$sqlList = $cnx->prepare("DELETE FROM list where idList = ?");
		$sqlList->bindValue(1, $idList);
		$sqlList->execute();
	}

	switch($request_method)
	{
		case 'GET':
			getLists();
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