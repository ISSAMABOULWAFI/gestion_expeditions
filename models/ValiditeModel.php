<?php

class Validite {
	public static function addValidite($id_order,$id_exp,$nbr_colis,$lc){
		//$sql = "SELECT o.id_order as id_order,IFNULL(nbr_colis,1) as nbr_colis,current_state,reference FROM "._DB_PREFIX_."orders o LEFT JOIN "._DB_PREFIX_."commande_tmp c ON (o.id_order=c.id_order) where current_state='".$stat."'";
		Db::getInstance()->insert('my_exp_cmd_159357', array(
			'id_order' => $id_order,
			'id_exp' => $id_exp,
			'nbr_colis' => $nbr_colis,
			
		));
		Db::getInstance()->update('order_carrier', array(
			'tracking_number' => $lc,
		),'id_order=\''.$id_order.'\'');
		
	}
	
	public static function setValidite($id_order,$is_valid){
		//$sql = "SELECT o.id_order as id_order,IFNULL(nbr_colis,1) as nbr_colis,current_state,reference FROM "._DB_PREFIX_."orders o LEFT JOIN "._DB_PREFIX_."commande_tmp c ON (o.id_order=c.id_order) where current_state='".$stat."'";
		
		$data = array('is_valid' => $is_valid);
        $where = 'id_order = '.$id_order.' ';
        Db::getInstance()->update('my_exp_cmd_159357', $data, $where);
			
	}
	
}
?>