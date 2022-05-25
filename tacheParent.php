<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");
		
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