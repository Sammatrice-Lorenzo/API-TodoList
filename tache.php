<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");
		
	function getTaches($idList) {
		global $cnx;
		$sql = $cnx->prepare("select idTache, nomTache, idType, idList from tache where idList = ?");
		$sql->bindValue(1, $idList);
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$tache = [
				'id' => $row['idTache'],
				'nom' => $row['nomTache'],
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

		$sql = $cnx->prepare("INSERT INTO tache (nomtache, idtype, idlist) VALUES (?, ?, ?)");
		$sql->bindValue(1, $tache->nom);
		$sql->bindValue(2, $tache->idtype);
		$sql->bindValue(3, $tache->idlist);
		$sql->execute();
	}
	
	function update()
	{
		global $cnx;
		$json_str = file_get_contents('php://input');
		$tache = json_decode($json_str);
		
		$sql= $cnx->prepare("UPDATE tache set nomtache = ?, idType = ?, idList = ? where idtache = ?");
		$sql->bindValue(1, $tache->nom);
		$sql->bindValue(2, $tache->idtype);
		$sql->bindValue(3, $tache->idlist);
		$sql->bindValue(4, $tache->id);
		$sql->execute();
	}
	
	function delete() {
		global $cnx;
		
		$json_str = file_get_contents('php://input');
		$tache = json_decode($json_str);
		
		$sql= $cnx->prepare("DELETE FROM tache where idtache = ?");
		$sql->bindValue(1, $tache->id);
		$sql->execute();
	}

	switch($request_method)
	{
		case 'GET':
			getTaches($_GET['idList']);
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