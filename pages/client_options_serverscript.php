<?php
	header('Content-Type: application/json');
	if(!$logged) {
		echo '{"errorcode":-2}'; // player is not logged
		return;
	}
	
	$res = $SQL->query("SELECT * FROM account_options WHERE id = ".$account_logged->getID())->fetch();
	if($data = json_decode(file_get_contents("php://input"))) {
		if($sid = @$data->{"sid"}){
			echo '{"errorcode":0,"token":"' . Website::generateSessionKey() . '"}';
		}
		
		if($options = @$data->{"options"} && $token = @$data->{"token"}) {
			if($res["options"] == "") {
				$SQL->query("INSERT INTO account_options VALUES(".$account_logged->getID().", '".$data->{"options"}."')");
			} else {
				$SQL->query("UPDATE account_options SET options = '".str_replace("\n", "\\n", $data->{"options"})."' WHERE id = ".$account_logged->getID()."");
			}
			echo '{"errorcode":0}';
		}
		
		return;
	}
	
	$options = str_replace("\n", "\\n", $res["options"]);
	if($options == "") {
		echo '{"errorcode":-1}'; // player does not have any options
		return;
	}
	
	echo '{"errorcode":0,"options":"'.$options.'"}';
?>
