<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");
	
	function getLists() {
		global $cnx;
		$sql = $cnx->prepare("select idList, nomList from list");
		$sql->execute();
		$response = [];
		
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$list = [
				'id' => $row['idList'],
				'nom' => $row['nomList'],
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

		$sql= $cnx->prepare("UPDATE list set nomList = ? where idList = ?");
		$sql->bindValue(1, $list->nom);
		$sql->bindValue(2, $list->id);
		$sql->execute();
	}
	
	function delete() {
		global $cnx;
		
		$json_str = file_get_contents('php://input');
		$list = json_decode($json_str);

		$sqlTaches = $cnx->prepare("DELETE FROM tache where idList = ?");
		$sqlTaches->bindValue(1, $list->id);
		$sqlTaches->execute();
		
		$sqlList = $cnx->prepare("DELETE FROM list where idList = ?");
		$sqlList->bindValue(1, $list->id);
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
			delete();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>