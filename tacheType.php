<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	header("Access-Control-Allow-Origin: *");
		
	function getTacheType() {
		global $cnx;
		$sql = $cnx->prepare("select tache.idTache, type.nomType FROM tache INNER JOIN type ON tache.idType = type.idType");
		$sql->execute();
		$response = [];

		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$tache = [
				'id' => $row['idTache'],
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
			getTacheType();
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>