<?php

if(!defined('_PS_VERSION_'))
	exit();

require_once dirname(__FILE__).'/../../models/MagasinModel.php';
require_once dirname(__FILE__).'/../../models/CodeAmana.php';

class AdminMagController extends AdminController{
	public function __construct(){
		$this->bootstrap = true;

		
		parent::__construct();
		
	}
	public function getTemplatePath()
	{
		return dirname(__FILE__).'/../../views/templates/admin/';
	}
		
	public function createTemplate($tpl_name) {
        if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
            return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
            return parent::createTemplate($tpl_name);
    }
	public function initContent(){
		$action=Tools::getValue('action');
		
		$smarty=$this->context->smarty;
		$adminMagLink=$this->context->link->getAdminLink('AdminMag');
		$smarty->assign('adminMagLink',$adminMagLink);
		
		if(Tools::getValue('zone_name')!=null && Tools::getValue('action')!=null){
			$action=Tools::getValue('action');
			$zone_name=Tools::getValue('zone_name');
			if($action=="add"){
				Magasin::addZone($zone_name);
			}
			if($action=="remove"){
				Magasin::removeZone($zone_name);
			}
			die();
		}
		if(Tools::getValue('libelle')!=null && Tools::getValue('action')!=null){
			$action=Tools::getValue('action');
			$libelle=Tools::getValue('libelle');
			
			if($action=="addCode"){
				$code=Tools::getValue('code');
				CodeAmana::addCode($libelle,$code);
			}
			if($action=="remove"){
				CodeAmana::removeCode($libelle);
			}
			die();
		}
		if(Tools::isSubmit('submitbtn')){
			$id_mag=Tools::getValue('id_mag');
			
			//
			if($id_mag){
				
				
				$id_cd_in=Tools::getValue('id_cd_in');
				$cd_in=Tools::getValue('cd_in');
				if($id_cd_in){
					//echo "update<br/>";
					Magasin::update($id_cd_in,$cd_in);
				}else{
					//echo "insert<br/>";
					
					Magasin::add($cd_in,'in',$id_mag);
				}
				
				
				
				$id_cd_begin=Tools::getValue('id_cd_begin');
				$cd_begin=Tools::getValue('cd_begin');
				if($id_cd_begin){
					//echo "update<br/>";
					Magasin::update($id_cd_begin,$cd_begin);
				}else{
					//echo "insert<br/>";
					Magasin::add($cd_begin,'com',$id_mag);
				}
				
				$to_delete=Tools::getValue('to_delete');
				if($to_delete!="" && $to_delete!=null ){
					Magasin::deleteList($id_mag,$to_delete);
				}
				
				
				$id_cd_bet_array=Tools::getValue('id_cd_bet');
				$cd_bet_from_array=Tools::getValue('cd_bet_from');
				$cd_bet_to_array=Tools::getValue('cd_bet_to');
				
				
				
				for($i=0;$i<count($id_cd_bet_array);$i++) {
					if($id_cd_bet_array[$i]){
						Magasin::updateRange($id_cd_bet_array[$i],$cd_bet_from_array[$i],$cd_bet_to_array[$i]);
						//echo "update ".$i."<br/>";
					}else{
						if($cd_bet_from_array[$i]!=null && $cd_bet_to_array[$i]!=null){
							Magasin::addRange($cd_bet_from_array[$i],$cd_bet_to_array[$i],$id_mag);
							//	echo "insert ".$i."<br/>";
						}
					}
				}
				
				$is_actif=Tools::getValue('is_active');
				if($is_actif){
					Magasin::set_actif($id_mag,1);
				}else{
					Magasin::set_actif($id_mag,0);
				}
				
				$is_default=Tools::getValue('is_default');
				if($is_default){
					Magasin::set_default($id_mag);
				}
				
				//echo Magasin::addOrUpdate(Tools::getValue('id_mag'),Tools::getValue('id_cd_in'),1);
				/*echo "<pre>";
				print_r($_POST);
				echo "</pre>";*/
				//die();
			}
			header('Location: '.$adminMagLink.'&id_mag='.$id_mag);
		}
		if(Tools::isSubmit('submitbtn2')){
			$s="";
			
			if(isset($_POST['carrier'])){
				$items=$_POST['carrier'];
				foreach($items as $c){
					$s.=$c;
					
					if(next( $items ) )
						$s.=",";
				}
			}
			//echo $s;
			Configuration::updateValue('carriers_159357',$s);
			
		}
		
		
		if(Tools::isSubmit('submitbtn3')){
			//print_r($_POST);
			
			
			$zone_id=Tools::getValue('zone_id');
			$zip_cd_zone=explode (',',Tools::getValue('zip_cd_zone'));
			//print_r($zip_cd_zone);
			//die();
			Magasin::setZoneCdIn($zone_id,$zip_cd_zone);
			
			
			$id_cd_bet=Tools::getValue('id_cd_bet');
			$cd_bet_from=Tools::getValue('cd_bet_from');
			$cd_bet_to=Tools::getValue('cd_bet_to');
			
			$to_delete2=Tools::getValue('to_delete2');
			Magasin::setZoneCdBet($zone_id,$id_cd_bet,$cd_bet_from,$cd_bet_to,$to_delete2);
			
		}
		//
		
		$id_mag = (isset($_GET['id_mag'])?(int)$_GET['id_mag']:-1);
		
		
		$list=Magasin::getListMagasin();
		$smarty->assign('listMagasin',$list);
		
		
		$carriers=Magasin::getCarriers();
		$selectedCarriers=explode(',',Configuration::get('carriers_159357'));
		for($i=0;$i<count($carriers);$i++){
			$carriers[$i]['enabled']='false';
			foreach($selectedCarriers as $sc){
				if($carriers[$i]['id_carrier']==$sc){
					$carriers[$i]['enabled']='true';
				}
			}
		}
		
		$smarty->assign('carriers',$carriers);
		
		
		if($id_mag==-1 && $list){
			$id_mag=(int)$list[0]['id_store'];
		}
		
		foreach($list as $m){
			if($m['id_store']==$id_mag){
				$is_active=$m['is_actif'];
				$smarty->assign('is_active',$is_active);
				$is_default=$m['is_default'];
				$smarty->assign('is_default',$is_default);
			}
		}
		
		$smarty->assign('mag',$id_mag);
		
		$cdPList=Magasin::getCdByStore($id_mag,'in');
		$smarty->assign('cdPList',$cdPList);
		
		$cdPBegin=Magasin::getCdByStore($id_mag,'com');
		$smarty->assign('cdPBegin',$cdPBegin);
		
		$cdPBetween=Magasin::getCdByStore($id_mag,'bet');
		$smarty->assign('cdPBetween',$cdPBetween);
		
		$zones=Magasin::getZones();
		$smarty->assign('zones',$zones);
			
		
		if(isset($_GET['id_zone'])){
			$zones1=Magasin::getZone($_GET['id_zone']);
			$t="";
			if($zones1)
			foreach($zones1 as $z){
				$t.=$z['zip_code'].",";
				
			}
			$smarty->assign('cdp',$t);
			$smarty->assign('zone',$zones1);
			$smarty->assign('zoneBetween',Magasin::getZoneBetween($_GET['id_zone']));
		}else{
			
			
			$zones1=Magasin::getZone($zones[0]['id']);
			
			//$smarty->assign('test',$zones1[0]['id']);
			$t="";
			if($zones1)
			foreach($zones1 as $z){
				$t.=$z['zip_code'].",";
			}
			
			$smarty->assign('cdp',$t);
			$smarty->assign('zone',$zones1);
			$smarty->assign('zoneBetween',Magasin::getZoneBetween($zones1[0]['id']));
		}
		
		
		
		
		$smarty->assign('amanacodes',CodeAmana::getAll());
		
		$smarty->assign('action','main');
		
	
		
		$this->content=$this->createTemplate('AdminMag.tpl')->fetch();
		parent::initContent();
	}
	
}