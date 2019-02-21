<?php

if(!defined('_PS_VERSION_'))
	exit();



//require('../config/config.inc.php');
require_once '../classes/order/Order.php';
require_once '../classes/order/OrderHistory.php';

require_once dirname(__FILE__).'/../../models/ExportModel.php';

require_once dirname(__FILE__).'/../../models/ValiditeModel.php';
require_once dirname(__FILE__).'/../../models/OrderModel.php';



class AdminExpController extends AdminController{
	
	
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
		
		$smarty = $this->context->smarty;
		
		$smarty->assign('action', '');
		$smarty->assign('form', "");
		
		if(isset($_POST['submitBtn'])){
			//print_r($_POST);
			//die();
			$newExport=Export::addExport();
			
			//$newExport='13';
			
			$count=count($_POST['id_order']);
			
			for($k=0;$k<$count;$k++){
				
				$selected=$_POST['selected'][$k];
				if($selected=='0')
					continue;
				$id_ord=$_POST['id_order'][$k];
				$nbr=$_POST['nbr_colis'][$k];
				
				Validite::setValidite($id_ord,0);
				$lc='';
				$lcs=$this->generateLC($nbr);
				
				for($i=0;$i<((int)count($lcs));$i++)
						$lc.=$lcs[$i].',';
				//echo $lc."<br/>";
					
				Validite::addValidite($id_ord,$newExport,$nbr,$lc);
				//Order::update_state($id_ord,(int)Configuration::get('state2_159357'));
				
				$order_state_id=(int)Configuration::get('state2_159357');
				//$objOrder = new Order((object)array('id'=>'$id_ord')); //order with id=$_GET["action"]
				$objOrder = new Order($id_ord);
				//$objOrder->getFields();
				//echo $id_ord."<br/>";
				//print_r($objOrder);
				//die();
				$objOrder->setCurrentState($order_state_id);
				/*$history = new OrderHistory();
				$history->id_order = (int)$objOrder->id;*/
				//$history->changeIdOrderState($order_state_id, (int)($objOrder->id)); //order status=4
				//$history->id_order_state = $order_state_id;
				//$history->addWithemail(true);
				//$statusmeldung = '<h1 style="background: rgb(255,0,0); margin-top: 214px;">ORDER ID '.$_GET["order"].' auf Status '.$_GET["status"].' gesetzt!</h1>';
			}
			
			header('Location: '.$this->context->link->getAdminLink('AdminExp').'&action=detail&id_exp='.$newExport);
			
		}
		
		
			
		if(!isset($_GET['action'])){
			//print_r(Export::getExportsWithNbrOrders());
			//die();
			$smarty->assign('exp', Export::getExportsWithNbrOrders());
			$smarty->assign('action', "list");
			$smarty->assign('newlink', $this->context->link->getAdminLink('AdminExp').'&action=new');
			$linkdetail=$this->context->link->getAdminLink('AdminExp').'&action=detail&id_exp=';
			$smarty->assign('linkdetail', $linkdetail);
		
		}
		if(isset($_GET['action'])){
			$action=$_GET['action'];
			if($action=='new'){
				$smarty->assign('carriers', explode(',', Configuration::get('carriers_159357')));				
				$smarty->assign('orders', OrderModel::getOrders((int)Configuration::get('state1_159357')));				
			}
			if($action=='detail'){
				$export=Export::getExport($_GET['id_exp']);
				
				if($export){
					$smarty->assign('export', $export);
					$smarty->assign('orders', Export::getExportDetail($_GET['id_exp'],$this->context->language->id));
				}
				
				
				$smarty->assign('bordname', $this->format_nomination(new DateTime($export[0]['date_exp']),Configuration::get('nomination_bord_159357')));
				
				$smarty->assign('imporname', $this->format_nomination(new DateTime($export[0]['date_exp']),Configuration::get('nomination_import_159357')));
				
				$smarty->assign('num', $this->getN());
				
				
				$tmp_date=strtotime(date('Y-m-d h:i:sa'));
				$exp_tmp_date=strtotime($export[0]['date_exp']);
				//$exp_tmp_date=date('Y-m-d h:i:sa',$exp_tmp_date_str);
				//echo $exp_tmp_date;
				//die;
				$objet = Configuration::get("amana_mail_objet_159357");
				
				$objet=str_replace('#cd',date('d',$tmp_date),$objet);
				$objet=str_replace('#cm',date('m',$tmp_date),$objet);
				$objet=str_replace('#cY',date('Y',$tmp_date),$objet);
				$objet=str_replace('#cH',date('H',$tmp_date),$objet);
				$objet=str_replace('#ci',date('i',$tmp_date),$objet);
				$objet=str_replace('#cs',date('s',$tmp_date),$objet);
				
				$objet=str_replace('#d',date('d',$exp_tmp_date),$objet);
				$objet=str_replace('#m',date('m',$exp_tmp_date),$objet);
				$objet=str_replace('#Y',date('Y',$exp_tmp_date),$objet);
				$objet=str_replace('#H',date('H',$exp_tmp_date),$objet);
				$objet=str_replace('#i',date('i',$exp_tmp_date),$objet);
				$objet=str_replace('#s',date('s',$exp_tmp_date),$objet);
				
				$smarty->assign('objet',$objet);
				//$smarty->assign('volum',);
				//$smarty->assign('encombr',Export::getOrdersEncombr($_GET['id_exp']));
				
				$volum=Export::getOrdersVolumin($_GET['id_exp']);
				$encomb= Export::getOrdersEncombr($_GET['id_exp']);
				
				$message = Configuration::get("amana_mail_message_159357");
						
				$message=str_replace('#cd',date('d',$tmp_date),$message);
				$message=str_replace('#cm',date('m',$tmp_date),$message);
				$message=str_replace('#cY',date('Y',$tmp_date),$message);
				$message=str_replace('#cH',date('H',$tmp_date),$message);
				$message=str_replace('#ci',date('i',$tmp_date),$message);
				$message=str_replace('#cs',date('s',$tmp_date),$message);
				
				$message=str_replace('#d',date('d',$exp_tmp_date),$message);
				$message=str_replace('#m',date('m',$exp_tmp_date),$message);
				$message=str_replace('#Y',date('Y',$exp_tmp_date),$message);
				$message=str_replace('#H',date('H',$exp_tmp_date),$message);
				$message=str_replace('#i',date('i',$exp_tmp_date),$message);
				$message=str_replace('#s',date('s',$exp_tmp_date),$message);
				
				
				if($volum){
					$tt='<ul>';
					foreach($volum as $v){
						$tt.='<li>'.$v['tracking_number'].'</li>';
					}
					$tt.='</ul>';
					$message=str_replace('#Volumineux',$tt,$message);
				}else{
					$message=str_replace('#Volumineux','<ul><li>Aucun</li></ul>',$message);
				}
				
				if($encomb){
					$tt='<ul>';
					foreach($encomb as $e){
						$tt.='<li>Commande N° '.$e['id_order'].'</li>';
						$lcs=explode(',',$e['tracking_number']);
						$tt.="<ul>";
						foreach($lcs as $l){
							if($l)
							$tt.="<li>".$l."</li>";
						}
						$tt.="</ul>";
					}
					$tt.='</ul>';
					$message=str_replace('#Encombrants',$tt,$message);
				}else{
					$message=str_replace('#Encombrants','<ul><li>Aucun</li></ul>',$message);
				}
				
				
				
				$smarty->assign('message',$message);
				
				
				
				$zonesList=Magasin::getZones();
				for($i=0;$i<count($zonesList);$i++){
					$m=Export::getOrdersByZone($_GET['id_exp'],$this->context->language->id,$zonesList[$i]['id']);
					
					$zonesList[$i]['nbr']=count($m);
				}
				$defaultzone=count(Export::getOrdersByZone($_GET['id_exp'],$this->context->language->id,-1));
				$smarty->assign('defaultzone', $defaultzone);
				
				$smarty->assign('zonesList', $zonesList);
				
				//BORDEREAU LINKS
				$smarty->assign('pdflink', $this->context->link->getAdminLink('AdminGenerationDocs').'&format=pdf&id_exp='.$_GET['id_exp']);
				
				
				$smarty->assign('ticketlink', $this->context->link->getAdminLink('AdminGenerationDocs').'&format=etiquettes&id_exp='.$_GET['id_exp']);
				$myorders=Export::getExportDetail2($_GET['id_exp'],$this->context->language->id);
				$smarty->assign('nbrtickets', count($myorders));
				$smarty->assign('xlslink', $this->context->link->getAdminLink('AdminGenerationDocs').'&format=xls&id_exp='.$_GET['id_exp'].'&op=true');
				$smarty->assign('maillink', $this->context->link->getAdminLink('AdminGenerationDocs').'&format=mail');
				$smarty->assign('mailclientlink', $this->context->link->getAdminLink('AdminGenerationDocs').'&format=mailClient');
			}
			$smarty->assign('action', $action);
			
			$smarty->assign('finishLink', $this->context->link->getAdminLink('AdminExp'));
			$smarty->assign('imgDir',  dirname(__FILE__).'/../../images/');	
		}
		
		$this->content=$this->createTemplate('AdminExp.tpl')->fetch();
		parent::initContent();
	}
	public static function getN(){
		$current_date=date("dmY");
		
		
		$vars=explode(',',Configuration::get('nomination_159357'));
		
		
		if(count($vars)>1){
			$dt=$vars[0];
			$n=$vars[1];
			if($dt!=$current_date){
				$n=0;
			}
		}else{
			$n=0;
			
		}
		
		
		return $n;
		//$model=str_replace('#n',$n,$model);
		
	}
	public function format_nomination($date,$s){
		$model=$s;
		$tmp_date=strtotime(date('Y-m-d h:i:sa'));
		
		
		$model=str_replace('#cd',date('d',$tmp_date),$model);
		$model=str_replace('#cm',date('m',$tmp_date),$model);
		$model=str_replace('#cY',date('Y',$tmp_date),$model);
		$model=str_replace('#cH',date('H',$tmp_date),$model);
		$model=str_replace('#ci',date('i',$tmp_date),$model);
		$model=str_replace('#cs',date('s',$tmp_date),$model);
		
		
		$model=str_replace('#d',$date->format('d'),$model);
		$model=str_replace('#m',$date->format('m'),$model);
		$model=str_replace('#Y',$date->format('Y'),$model);
		$model=str_replace('#H',$date->format('H'),$model);
		$model=str_replace('#i',$date->format('i'),$model);
		$model=str_replace('#s',$date->format('s'),$model);
		
		return $model;
	}
	public function setMedia()
	{
	  parent::setMedia();
	  $this->addJs(dirname(__FILE__).'/../../js/dynamitable.jquery.min.js');
	  
	  $this->addJs(dirname(__FILE__).'/../../js/multiple-emails.js');
	  
	  $this->addCss(dirname(__FILE__).'/../../css/multiple-emails.css');
	  $this->addJs(dirname(__FILE__).'/../../js/script.js');
	  $this->addCss(dirname(__FILE__).'/../../css/jquery-te-1.4.0.css');
	  $this->addJs(dirname(__FILE__).'/../../js/jquery-te-1.4.0.min.js');
	  
	  
	  
	}
	private function generateLC($nbr)
	{
		$p=array();
		$current_lc=(int)Configuration::get('last_lc_159357');
		for($i=0;$i<((int)$nbr);$i++){
			$current_lc=$current_lc+1;
			$p[]=Configuration::get('prefix_lc_159357').$this->get_new_lc($current_lc).Configuration::get('sufix_lc_159357');
		}
		Configuration::updateValue('last_lc_159357', $current_lc);
	    return $p;
	}
	private function get_new_lc($CODE){
		$CODE=strval($CODE);
		$x1=($CODE[0]*8)+($CODE[1]*6)+($CODE[2]*4)+($CODE[3]*2)+($CODE[4]*3)+($CODE[5]*5)+($CODE[6]*9)+($CODE[7]*7);
		$y=11-($x1%11);
		
		if($y==10){
			$c='0';
		}else if($y==11){
			$c='5';
		}else{
			$c=$y;
		}
		
		return $CODE.$c;
	}
	

}