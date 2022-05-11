<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");
	
	function getTypes() {
		global $cnx;
		$sql = $cnx->prepare("select idtype, nomtype, idparent from type");
		$sql->execute();
		$response = [];
		
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$type = [
				'id' => $row['idType'],
				'nom' => $row['nomType'],
				'idparent' => $row['idParent'],
			];
			$response[] = $type;
		}

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	function insert() {
		global $cnx;
		$json_str = file_get_contents('php://input');
		$type = json_decode($json_str);

		$sql = $cnx->prepare("INSERT INTO type (nomtype, idparent) VALUES (?, ?)");
		$sql->bindValue(1, $type->nom);
		$sql->bindValue(2, $type->idparent);
		$sql->execute();
	}
	
	function update()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$type = json_decode($json_str);
		
		$sql= $cnx->prepare("UPDATE type set idtype = ?, nomtype = ?, idParent = ? where idtype = ?");
		$sql->bindValue(1, $type->id);
		$sql->bindValue(2, $type->nom);
		$sql->bindValue(3, $type->idparent);
		$sql->bindValue(4, $type->id);
		$sql->execute();
	}
	
	function delete() {
		global $cnx;

		$json_str = file_get_contents('php://input');
		$type = json_decode($json_str);
	
		$sqlDeleteParent = $cnx->prepare("DELETE FROM type where idparent = ?");
		$sqlDeleteParent->bindValue(1, $type->id);
		$sqlDeleteParent->execute();

		$sqlDeleteType = $cnx->prepare("DELETE FROM type where idtype = ?");
		$sqlDeleteType->bindValue(1, $type->id);
		$sqlDeleteType->execute();
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
			delete();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>