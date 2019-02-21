<?php
class OrderModel {
	public static function getOrders($stat){
		$sql = 'SELECT o.id_order as id_order,reference,firstname,lastname,o.date_add as date_add,o.id_carrier as id_carrier '.
		'FROM '._DB_PREFIX_.'orders o ,'._DB_PREFIX_.'customer cs '.
		'WHERE    cs.id_customer=o.id_customer and current_state=\''.$stat.'\'';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function addOrderTmp($id_order,$id_export,$nbr_colis,$lc){
		Db::getInstance()->insert('commande_tmp', array(
			'id_order' => $id_order,
			'id_export' => $id_export,
			'nbr_colis' => $nbr_colis,
			'lc' => $lc,
		));
	}
	public static function update_state($id_order,$newState){
		Db::getInstance()->update('orders', array(
			'current_state' => $newState,
		),'id_order='.$id_order);
	}
	public static function get_relay_point($id_cart){
		$sql = 'SELECT t.id_mymod_carrier_cart,t.id_cart,t.relay_point,t.date_add '.
		'FROM '._DB_PREFIX_.'decatlon_relay_carrier_cart t '.
		'WHERE    t.id_cart=\''.$id_cart.'\'';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	
}
?>