<?php

class Export {
	public static function getExports(){
		$sql = 'SELECT id_exp,date_exp,date_mail_amana,date_mail_client FROM '._DB_PREFIX_.'my_expeditions_159357 order by date_exp desc ';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getExportsWithNbrOrders(){
		$sql = 'SELECT id_exp,date_exp,date_mail_amana,date_mail_client,(select count(*) from '._DB_PREFIX_.'my_exp_cmd_159357 where id_exp= m.id_exp) as nbr_orders, (select sum(nbr_colis) from '._DB_PREFIX_.'my_exp_cmd_159357 where id_exp= m.id_exp) as nbr_colis  
				FROM '._DB_PREFIX_.'my_expeditions_159357 m  
				order by date_exp desc';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function addExport(){
		if(Db::getInstance()->insert('my_expeditions_159357', array(
			'id_exp' => '',
		))){
			return Db::getInstance()->Insert_ID();
		}
		return -1;
	}
	public static function getExport($id_export){
		$sql = 'SELECT id_exp,date_exp,(select count(*) from '._DB_PREFIX_.'my_exp_cmd_159357 where id_exp= m.id_exp) as nbr_orders,(select sum(nbr_colis) from '._DB_PREFIX_.'my_exp_cmd_159357 where id_exp= m.id_exp) as nbr_colis  '. 
		       'FROM '._DB_PREFIX_.'my_expeditions_159357 m WHERE m.id_exp=\''.$id_export.'\'';
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	
	public static function getOrdersByZone($id_export,$id_lang,$zone_id){
		
		if($zone_id==-1){
			
			
		$sql='SELECT  c.id_order as id_order,
				nbr_colis,
				oc.tracking_number,
				reference,
				cs.firstname,
				cs.lastname,
				o.date_add as date_add,
				oc.weight as weight,
				o.total_shipping as shipping,
				o.total_paid as total_ttc,
				o.total_products as total_ht,
				o.total_paid_tax_excl as total_ht_shipp,
				o.total_products_wt as total_products_wt,
				o.id_cart,
				o.payment,
				c.is_valid as is_valid,
				a.address1 as address1,
				a.address2 as address2,
				a.postcode as postcode,
				a.city as city,
				a.district as district,
				a.phone_mobile as phone_mobile,
				cl.name as country_name ';
		$sql.='FROM  '._DB_PREFIX_.'my_exp_cmd_159357 c ,
					 '._DB_PREFIX_.'orders o ,
					 '._DB_PREFIX_.'customer cs , 
					 '._DB_PREFIX_.'order_carrier oc, 
					 '._DB_PREFIX_.'address a  ,
					 '._DB_PREFIX_.'country_lang cl ';
	 
		$sql.='WHERE c.id_exp=\''.$id_export.'\'  
		and c.id_order=o.id_order 
		and c.is_valid>0 
		and cs.id_customer=o.id_customer 
		and oc.id_order=o.id_order 
		and a.id_address=o.id_address_delivery 
		AND cl.id_country=a.id_country 
		and cl.id_lang=\''.$id_lang.'\'  
		and c.id_order not in (SELECT  c.id_order as id_order 
									 FROM '._DB_PREFIX_.'orders o ,
									      '._DB_PREFIX_.'address a  ,
									      '._DB_PREFIX_.'my_amana_zones_between_159357 znb 
									WHERE  a.id_address=o.id_address_delivery 
										   and (postcode in (select zip_code 
																	from '._DB_PREFIX_.'my_amana_zones_in_159357 
																	) 
												   or postcode between znb.from_ and znb.to_))';
									  
			
		
		}else{
			$sql = 'SELECT DISTINCT c.id_order as id_order,
							nbr_colis,
							oc.tracking_number,
							reference,
							cs.firstname,
							cs.lastname,
							o.date_add as date_add,
							oc.weight as weight,
							o.total_shipping as shipping,
							o.total_paid as total_ttc,
							o.total_products as total_ht,
							o.total_paid_tax_excl as total_ht_shipp,
							o.total_products_wt as total_products_wt,
							o.id_cart,
							o.payment,
							c.is_valid as is_valid,
							a.address1 as address1,
							a.address2 as address2,
							a.postcode as postcode,
							a.city as city,
							a.district as district,
							a.phone_mobile as phone_mobile,
							cl.name as country_name ';
			$sql.= 'FROM '._DB_PREFIX_.'my_exp_cmd_159357 c ,'
						   ._DB_PREFIX_.'orders o ,'
						   ._DB_PREFIX_.'customer cs , '
						   ._DB_PREFIX_.'order_carrier oc, '
						   ._DB_PREFIX_.'address a  ,'
						   ._DB_PREFIX_.'country_lang cl, '
						   ._DB_PREFIX_.'my_amana_zones_between_159357 znb ';
			$sql.= 'WHERE c.id_exp=\''.$id_export.'\' 
					   and c.id_order=o.id_order 
					   and cs.id_customer=o.id_customer 
					   and oc.id_order=o.id_order 
					   and a.id_address=o.id_address_delivery 
					   and cl.id_country=a.id_country 
					   and c.is_valid>0 
					   and cl.id_lang='.$id_lang.' 
					   and (postcode in (select zip_code 
											from '._DB_PREFIX_.'my_amana_zones_in_159357 
											) 
					   or postcode between znb.from_ and znb.to_)
					   and znb.id_zone=\''.$zone_id.'\'';
		}
		
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	
	public static function getExportDetail($id_export,$id_lang){
		$sql = 'SELECT c.id_order as id_order,nbr_colis,oc.tracking_number,reference,cs.firstname,cs.lastname,o.date_add as date_add,oc.weight as weight,o.total_shipping as shipping,o.total_paid as total_ttc,o.total_products as total_ht,o.total_paid_tax_excl as total_ht_shipp,o.total_products_wt as total_products_wt,o.payment,c.is_valid as is_valid,a.address1 as address1,a.address2 as address2,a.postcode as postcode,a.city as city,a.district as district,a.phone_mobile as phone_mobile,cl.name as country_name '.
		'FROM '._DB_PREFIX_.'my_exp_cmd_159357 c ,'._DB_PREFIX_.'orders o ,'._DB_PREFIX_.'customer cs , '._DB_PREFIX_.'order_carrier oc, '._DB_PREFIX_.'address a  ,'._DB_PREFIX_.'country_lang cl ';
	
		$sql.='WHERE c.id_exp=\''.$id_export.'\' and c.id_order=o.id_order and cs.id_customer=o.id_customer and oc.id_order=o.id_order and a.id_address=o.id_address_delivery AND cl.id_country=a.id_country and cl.id_lang='.$id_lang.' ';
		
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getExportDetail2($id_export,$id_lang){
		$sql = 'SELECT c.id_order as id_order,nbr_colis,oc.tracking_number,reference,cs.firstname,cs.lastname,o.date_add as date_add,oc.weight as weight,o.total_shipping as shipping,o.total_paid as total_ttc,o.total_products as total_ht,o.total_paid_tax_excl as total_ht_shipp,o.total_products_wt as total_products_wt,o.payment,c.is_valid as is_valid,a.address1 as address1,a.address2 as address2,a.postcode as postcode,a.city as city,a.district as district,a.phone_mobile as phone_mobile,cl.name as country_name '.
		'FROM '._DB_PREFIX_.'my_exp_cmd_159357 c ,'._DB_PREFIX_.'orders o ,'._DB_PREFIX_.'customer cs , '._DB_PREFIX_.'order_carrier oc, '._DB_PREFIX_.'address a  ,'._DB_PREFIX_.'country_lang cl ';
	
		$sql.='WHERE c.id_exp=\''.$id_export.'\' and c.id_order=o.id_order and cs.id_customer=o.id_customer and oc.id_order=o.id_order and a.id_address=o.id_address_delivery AND cl.id_country=a.id_country and cl.id_lang='.$id_lang.' and c.is_valid>0';
		
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getExportDetailForMail($id_export,$id_lang,$zone_id=-1){
		$sql = 'SELECT c.id_order as id_order,nbr_colis,oc.tracking_number,reference,cs.firstname,cs.lastname,o.date_add as date_add,oc.weight as weight,o.total_shipping as shipping,o.total_paid as total_ttc,o.total_products as total_ht,o.total_paid_tax_excl as total_ht_shipp,o.total_products_wt as total_products_wt,o.payment,c.is_valid as is_valid,a.address1 as address1,a.address2 as address2,a.postcode as postcode,a.city as city,a.district as district,a.phone_mobile as phone_mobile,cl.name as country_name '.
		'FROM '._DB_PREFIX_.'my_exp_cmd_159357 c ,'._DB_PREFIX_.'orders o ,'._DB_PREFIX_.'customer cs , '._DB_PREFIX_.'order_carrier oc, '._DB_PREFIX_.'address a  ,'._DB_PREFIX_.'country_lang cl ';
	
		if($zone_id!=null and $zone_id!=-1)
		$sql.=' ,'._DB_PREFIX_.'my_amana_zones_159357 zn,'._DB_PREFIX_.'my_amana_zones_between_159357  znb ';
		if($zone_id!=null and $zone_id==-1)
		$sql.=' ,'._DB_PREFIX_.'my_amana_zones_159357 zn,'._DB_PREFIX_.'my_amana_zones_between_159357  znb ';
	
		$sql.='WHERE c.id_exp=\''.$id_export.'\' and c.id_order=o.id_order and cs.id_customer=o.id_customer and oc.id_order=o.id_order and a.id_address=o.id_address_delivery AND cl.id_country=a.id_country and cl.id_lang='.$id_lang.' ';
		
		if($zone_id!=null && $zone_id!=-1 )
		$sql.='  and zn.id=znb.id_zone and zn.id=\''.$zone_id.'\' and (a.postcode between znb.from_ and znb.to_ or FIND_IN_SET(postcode,(select zip_codes from '._DB_PREFIX_.'my_amana_zones_159357 where id=\''.$zone_id.'\' )) > 0) ';
		
		if($zone_id!=null && $zone_id==-1 )
		$sql.='  and zn.id=znb.id_zone and ( NOT (a.postcode between znb.from_ and znb.to_) and NOT (FIND_IN_SET(postcode,(select zip_codes from '._DB_PREFIX_.'my_amana_zones_159357 where id=\''.$zone_id.'\' )) > 0)) ';
		
		
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function setMailSended($id_exp,$type_mail){
		$date = date('Y-m-d H:i:s');
		if($type_mail=='amana')
		Db::getInstance()->update('my_expeditions_159357', array(
			'date_mail_amana' => $date,
		),'id_exp='.$id_exp);
		
		if($type_mail=='clients')
		Db::getInstance()->update('my_expeditions_159357', array(
			'date_mail_client' => $date,
		),'id_exp='.$id_exp);
	}
	
	public static function getN($date){
		//$l=date_format($date,"dmY");
		
		//$t[]=$l;
		/*$sql = "select * ".
		"FROM "._DB_PREFIX_."my_expeditions_159357 ".
		"WHERE DATE_FORMAT(date_exp, '%d/%m/%Y')='".$date."' order by id_exp";
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;*/
	}
	public static function getOrdersVolumin($id_exp){
		$sql = "select * ".
		"FROM "._DB_PREFIX_."my_exp_cmd_159357 a, ".
		        _DB_PREFIX_."order_carrier b ".
				
		"WHERE a.id_order=b.id_order 
		  and  nbr_colis=1 
		  and b.weight>30 
		  
		  and a.id_exp='".$id_exp."' and is_valid>0";
		  
		  //and FIND_IN_SET(b.id_carrier ,('".Configuration::get('carriers_159357')."')) > 0 
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getOrdersEncombr($id_exp){
		$sql = "select * ".
		"FROM "._DB_PREFIX_."my_exp_cmd_159357 a,"._DB_PREFIX_."order_carrier oc ".
		"WHERE a.id_order=oc.id_order and  a.id_exp='".$id_exp."' and nbr_colis>1  and is_valid>0";
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
	public static function getExportOrders($id_exp){
		$sql = "select distinct(id_order) AS id_order ".
		"FROM "._DB_PREFIX_."my_exp_cmd_159357 a ".
		"WHERE a.id_exp='".$id_exp."'  and is_valid>0 ";
		if ($results = Db::getInstance()->ExecuteS($sql))
			return $results;
	}
}
?>