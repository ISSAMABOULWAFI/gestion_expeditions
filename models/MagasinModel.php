<?php 

class Magasin{
	public static function getListMagasin(){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'store');
	}
	public static function getListMagasin_1_7(){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'store s ,'._DB_PREFIX_.'store_lang sl  where s.id_store=sl.id_store  ');
	}
	public static function getMagasin($id_store){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'store where id_store=\''.$id_store.'\'');
	}
	public static function getDefaultMagasin(){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'store where is_default=\'1\'');
	}
	public static function getCdByStore($id_store,$type){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'my_cdPost_159357 where id_store=\''.$id_store.'\' and operation_=\''.$type.'\'');
	}
	public static function add($cd_,$op,$id_mag){
		//return Db::getInstance()->ExecuteS('');
		
		Db::getInstance()->insert('my_cdPost_159357', array(
			'from_' => $cd_,
			'operation_' => $op,
			'id_store' => $id_mag,
		));
	}
	public static function update($id_cd_,$cd_){
		
		Db::getInstance()->update('my_cdPost_159357', array(
			'from_' => $cd_,
		),' id_cd='.$id_cd_);
	}
	public static function addRange($from,$to,$id_mag){
		//return Db::getInstance()->ExecuteS('');
		
		Db::getInstance()->insert('my_cdPost_159357', array(
			'from_' => $from,
			'to_' => $to,
			'operation_' => 'bet',
			'id_store' => $id_mag,
		));
	}
	public static function updateRange($id_cd_,$from,$to){
		
		Db::getInstance()->update('my_cdPost_159357', array(
			'from_' => $from,
			'to_' => $to,
		),' id_cd='.$id_cd_);
	}
	
	public static function deleteList($id_mag,$to_delete){
		$to_delete=substr($to_delete, 0, -1);
		Db::getInstance()->delete('my_cdPost_159357', "id_store=".$id_mag." and id_cd in (".$to_delete.")");
	}
	public static function set_actif($id_mag,$is_actif){
		
		Db::getInstance()->update('store', array(
			'is_actif' => $is_actif,
		),' id_store='.$id_mag);
	}
	public static function set_default($id_mag){
		Db::getInstance()->Execute('UPDATE  '._DB_PREFIX_.'store
		SET     is_default = IF(id_store = '.$id_mag.', 1, 0)
		where 1');


	}
	public static function getMagasinByCdPostIN($cdPost){
		return Db::getInstance()->ExecuteS("select * from "._DB_PREFIX_."my_cdPost_159357 where FIND_IN_SET('".$cdPost."', from_) > 0 and operation_='in'");
	}
	public static function getMagasinByCdPostBET($cdPost){
		return Db::getInstance()->ExecuteS("select * from "._DB_PREFIX_."my_cdPost_159357 where ('".$cdPost."' between (from_) and (to_)) and operation_='bet'");
	}
	/*public static function getMagasinByCdPostIN($cdPost){
		return Db::getInstance()->ExecuteS('select * from '._DB_PREFIX_.'my_cdPost_159357 where '.$cdPost.' in (from_)');
	}*/
	public static function getMagasinByCdPostCOM($cdPostSub){
		
		$s=" ";
		$len=strlen($cdPostSub);
		for($i=1;$i<=$len;$i++){
			if($i>1)
			$s.=" or ";
			$s.="FIND_IN_SET('".substr($cdPostSub,0,$i)."', from_) > 0";
		}
		
		return Db::getInstance()->ExecuteS("select * from "._DB_PREFIX_."my_cdPost_159357 
		where operation_='com' and  (".$s.")");
	}
	public static function getAllStates($lang=1){
		//$sql = "SELECT o.id_order as id_order,IFNULL(nbr_colis,1) as nbr_colis,current_state,reference FROM "._DB_PREFIX_."orders o LEFT JOIN "._DB_PREFIX_."commande_tmp c ON (o.id_order=c.id_order) where current_state='".$stat."'";
		
		$sql = 'SELECT * '.
		'FROM '._DB_PREFIX_.'order_state_lang '.
		'WHERE    id_lang=\''.$lang.'\'';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getSimilarDestinationCd($city){
		$sql = 'SELECT * '.
		'FROM '._DB_PREFIX_.'my_amana_cd_159357 '.
		'WHERE    libelle = \''.$city.'\'';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getCarriers(){
		$sql = "select * ".
		"FROM "._DB_PREFIX_."carrier ";
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	
	public static function getZones(){
		$sql = "select * ".
		"FROM "._DB_PREFIX_."my_amana_zones_159357";
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getZone($id_zone){
		$sql = "select zn.id as id,zni.id as id_in,zone_name,zip_code ".
		"FROM "._DB_PREFIX_."my_amana_zones_159357 zn,"._DB_PREFIX_."my_amana_zones_in_159357 zni where zn.id='".$id_zone."' and zn.id=zni.id_zone";
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getZoneName($id_zone){
		$sql = "select id,zone_name ".
		"FROM "._DB_PREFIX_."my_amana_zones_159357 zn where zn.id='".$id_zone."'";
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getZoneBetween($id_zone){
		$sql = "select * ".
		"FROM "._DB_PREFIX_."my_amana_zones_between_159357 where id_zone='".$id_zone."'";
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	
	public static function addZone($zone_name){
		Db::getInstance()->insert('my_amana_zones_159357', array(
			'zone_name' => $zone_name,
		));
	}
	public static function removeZone($id_zone){
		Db::getInstance()->delete(
			'my_amana_zones_between_159357', 
			"id_zone='".$id_zone."'"
		);
		Db::getInstance()->delete(
			'my_amana_zones_159357', 
			"id='".$id_zone."'"
		);
	}
	public static function setZoneCdIn($id_zone,$cd_in){
		Db::getInstance()->delete('my_amana_zones_in_159357', 'id_zone='.$id_zone);
		
		
		if($cd_in)
		foreach($cd_in as $cd){
			if($cd!=0)
			Db::getInstance()->insert('my_amana_zones_in_159357', array(
				'zip_code' => $cd,
				'id_zone'      => $id_zone,
			));
		}
		
	}
	public static function setZoneCdBet($id_zone,$id_cd_bet,$cd_bet_from,$cd_bet_to,$to_delete2){
		
		for($i=0;$i<count($id_cd_bet);$i++){
			if($id_cd_bet[$i]==null && $cd_bet_from[$i]!=null && $cd_bet_to[$i]!=null){
				Db::getInstance()->insert('my_amana_zones_between_159357', array(
					'id_zone' => $id_zone,
					'from_' => $cd_bet_from[$i],
					'to_' => $cd_bet_to[$i],
				));
			}else{
				Db::getInstance()->update('my_amana_zones_between_159357', array(
					'from_' => $cd_bet_from[$i],
					'to_' => $cd_bet_to[$i],
				),' id_zone=\''.$id_zone.'\' and id=\''.$id_cd_bet[$i].'\' ');
			}
		}
		if($to_delete2!=null)
		Db::getInstance()->Execute("delete from "._DB_PREFIX_."my_amana_zones_between_159357 where id in (".substr($to_delete2,0,-1).")");
	}
	
}