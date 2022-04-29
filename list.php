<?php
	include "cnx.php";
	$request_method = $_SERVER["REQUEST_METHOD"];
	
	function getLists() {
		global $cnx;
		$sql = $cnx->prepare("select idList, nomList from list" );
		$sql->execute();
		$reponse = [];
		
		foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
			$list = [
				'id' => $row['idList'],
				'nom' => $row['nomList'],
			];
			$reponse[] = $list;
		}

		header('Content-Type: application/json');
		echo json_encode($reponse);
	}

	switch($request_method)
	{
		case 'GET':
			getLists();
			break;
		case 'POST':
			break;
		case 'PUT':
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>