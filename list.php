<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	
	function getLists() {
		global $cnx;
		$sql = $cnx->prepare("select idList, nomList from list");
		$sql->execute();
		$reponse = [];
		
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$list = [
				'id' => $row['idList'],
				'name' => $row['nomList'],
			];
			$reponse[] = $list;
		}

		header('Content-Type: application/json');
		echo json_encode($reponse);
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
		
		$sql= $cnx->prepare("DELETE FROM list where idList = ?");
		$sql->bindValue(1, $list->id);
		$sql->execute();
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
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>