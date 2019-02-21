<?php

if(!defined('_PS_VERSION_'))
	exit();

require_once dirname(__FILE__).'/../../models/MagasinModel.php';
require_once dirname(__FILE__).'/../../models/ExportModel.php';
require_once dirname(__FILE__).'/../../models/OrderModel.php';
require_once dirname(__FILE__).'/../../libs/TCPDF/includes.php';
require_once dirname(__FILE__).'/../../libs/TCPDF/tcpdf.php';

class AdminGenerationDocsController extends AdminController{
	public function __construct(){
		$this->bootstrap = true;

		
		parent::__construct();
		
	}
	
	public function initContent(){
		
		
		
		//$smarty = $this->context->smarty;
		
		//$smarty->assign('exp', Export::getExports());
		
		
		if(isset($_GET['format'])){
			$format=$_GET['format'];
			
			if($format=='pdf'){
				//$this->bordereauxPdf();
				$this->pdf(Tools::getValue('id_exp'),Tools::getValue('zone_id'));
			}
			
			if($format=='etiquettes'){
				//$this->bordereauxPdf();
				$this->etiquettes($_GET['id_exp']);
			}
			
			
			if($format=='xls'){
				
				$this->xls(Tools::getValue('id_exp'),Tools::getValue('zone_id'),Tools::getValue('op')) ;
			}
			if($format=='mail'){
				//print_r($_POST);
				$objet=$_POST['objet'];
				//$mails=list($_POST['mails']);
				$mails = json_decode($_POST['mails']);
				
				$message=$_POST['message'];
				
				
				$id_exp=Tools::getValue('id_exp');
				$this->mySendMail($id_exp,$objet,$mails,$message,$_POST['bord'],$_POST['impor']);
				//print_r($_POST);
				//echo $id_exp;
				die();
				
			}
			/*if($format=='mailClient'){
				print_r($_POST);
				die();
				$objet=$_POST['objet'];
				//$mails=list($_POST['mails']);
				$mails = json_decode($_POST['mails']);
				
				$message=$_POST['message'];
				//echo print_r($array);
				//die();
				//echo $mails;
				$files=array();
				if(isset($_POST['files']))
					$files=$_POST['files'];
				
				$id_exp=Tools::getValue('id_exp');
				$this->mySendMail($id_exp,$objet,$mails,$message,$files);
				//echo $mails;
				//Set the variables for the template:
				die();
				
			}*/
		}
		
		
        parent::initContent();
		
	}
	public function setXlsRowBorders($sheet,$row){
		$border_style= array('borders' => 
								array('right' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
											    ),
									  'bottom' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
											    ),
									   'top' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
											    ),
										'left' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
											    )
									)
		);
		for ($col = 'A'; $col != 'K'; $col++) {
			$sheet->getStyle($col.$row)->applyFromArray($border_style);
		}
		
	}
	
    public function xls($id_exp,$zone_id,$save=false) {
		$zone=Magasin::getZoneName($zone_id);
		$export=Export::getExport($id_exp);
		//print_r($orders);
		//die();
		$myorders=Export::getOrdersByZone($id_exp,$this->context->language->id,$zone_id);
		
		require_once dirname(__FILE__).'/../../libs/phpexcel/Classes/PHPExcel.php';
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("DECATHLON")
									 ->setLastModifiedBy("DECATHLON")
									 ->setTitle("Import en Masse")
									 ->setSubject("Import en Masse")
									 ->setDescription("Import en masse sera envoyé à AMANA.")
									 ->setKeywords("Import en Masse,DECATHLON")
									 ->setCategory("Import en Masse");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'PRODUIT')
					->setCellValue('B1', 'N° ENVOI')
					->setCellValue('C1', 'DESTINATION')
					->setCellValue('D1', 'POIDS')
					->setCellValue('E1', 'CRBT_CCP')
					->setCellValue('F1', 'VD')
					->setCellValue('G1', 'NOTIFICATION SMS')
					->setCellValue('H1', 'CODEPICKUP')
					->setCellValue('I1', 'Ville')
					->setCellValue('J1', 'DESTINATAIRE');

					
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
		
		
		for ($col = 'A'; $col != 'K'; $col++) {
			
			$this->cellColor($objPHPExcel,$col.'1', 'FFFF99');
			$objPHPExcel->getActiveSheet()->getStyle($col."1")->getFont()->setBold(true);
			
        }
		//$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$this->setXlsRowBorders($objPHPExcel->getActiveSheet(),'1');
			
		
		$loop=2;
		$prod_=Configuration::get('amana_code_produit_159357');
		
		foreach ($myorders as $row) {
			$count=0;
			if($row['is_valid']==0)
					continue;
			$lcs=explode(',',$row['tracking_number']);
			foreach ($lcs as $field) {
				if($field){
					$count+=1;
					$m=Magasin::getSimilarDestinationCd($row['district']);
					
					$objPHPExcel->getActiveSheet()
						->setCellValue('A'.$loop, $prod_)
						->setCellValue('B'.$loop, ''.$field.'')
						->setCellValue('C'.$loop, $m[0]['cd'])
						->setCellValue('D'.$loop, number_format($row['weight'],2,'.',''))
						->setCellValue('E'.$loop, (($this->ispaymentmtc($row['payment']))?(number_format(0,2,'.','')):(($count==1)?(number_format($row['total_ttc'],2,'.','')):(number_format(0,2,'.','')))))
						//number_format($row['total_ttc'],2)
						->setCellValue('G'.$loop, $row['phone_mobile'])
						->setCellValue('I'.$loop, $row['district'])
						->setCellValue('J'.$loop, $row['firstname'].' '.$row['lastname']);
						
					if(((int)$row['total_products_wt'])>((int)Configuration::get('seuil_garantie_159357'))){
						$objPHPExcel->getActiveSheet()
						->setCellValue('F'.$loop, $row['total_products_wt']);
					}	
					//->setCellValue('J'.$loop, $row['id_cart']);					
					//get_relay_point
					$relay_point=OrderModel::get_relay_point($row['id_cart']);
					if(count($relay_point)>0){
						$txt1=$relay_point[0]['relay_point'];
						
						
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$loop, substr ($txt1,strpos($txt1,'ID')+3));
					}
					$this->setXlsRowBorders($objPHPExcel->getActiveSheet(),$loop);
					
					$loop++;
				}
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Import en masse');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		// We'll be outputting an excel file
		//header('Content-type: application/vnd.ms-excel');

		// It will be called file.xls
		//header('Content-Disposition: attachment; filename="file.xls"');

		// Write file to the browser
		//The name of the folder.
		$folder = dirname(__FILE__).'/../../files';
		 
		//Get a list of all of the file names in the folder.
		$files = glob($folder . '/*');
		 
		//Loop through the file list.
		foreach($files as $file){
			//Make sure that this is a file and not a directory.
			if(is_file($file)){
				//Use the unlink function to delete the file.
				unlink($file);
			}
		}
		
		$filelink=dirname(__FILE__).'/../../files/';
		$filename='ImportEnMasse_'.$zone[0]['zone_name'].'_'.date("d-m-y", strtotime($export[0]['date_exp'])).'.xls';
		
		
		ob_end_clean();
		
		if($save=='true'){
			header('Content-type: application/vnd.ms-excel');

			header('Content-Disposition: attachment; filename="'.$filename.'"');
			
			$filename=dirname(__FILE__).'/../../files/file.xls';
			$objWriter->save("php://output");
			
		}else{
			
			$objWriter->save($filename);
			return $filename;
		}
    }
	public function mySendMail($id_exp,$objet,$mails,$message,$bord,$impo){
		
		$templateVars['{message}'] = $message;
		//$templateVars['{lastname}'] = "erramy";
		//$templateVars['{src_img}'] = _PS_BASE_URL_.__PS_BASE_URI__.'download/blog_belvg.png'; //Image to be displayed in the message 

		$id_land = Language::getIdByIso('fr'); 	//Set the English mail template
		$template_name = 'template'; //Specify the template file name
		$title = Mail::l(strval($objet)); //Mail subject with translation
		//$from = "tilila.ravi@gmail.com";   //Sender's email
		//$fromName = ""; //Sender's name
		$mailDir = dirname(__FILE__).'/../../views/templates/admin/mails/'; //Directory with message templates
		//$toName = ""; //Customer name
		$toMail = $mails; //Customer name
		
		//print_r($files);
		$fileAttachment=array();
		
		
		
		
		/*for($i=0;$i<count($files);$i++){
			//echo $files[$i]."<br/>";
			$fileAttachment[]=array(
				'content' => file_get_contents(dirname(__FILE__).'/../../files/'.$files[$i]),
				'name' => $files[$i],
				'mime' =>  'application/octet-stream'
			);
		}*/
		//$fileAttachment['content'] = file_get_contents(_PS_MODULE_DIR_.'etiquettes/controllers/admin/AdminPdf.xls'); //File path
		//$fileAttachment['name'] = 'fileAttachment.xls'; //Attachment filename
		//$fileAttachment['mime'] = 'application/octet-stream'; //mime file type
		//echo($bord);
		//die();
		
		
		$n=$this->getN();
		if($bord){
			for($i=0;$i<count($bord[0]);$i++){
				
				$ppp=$this->pdf($id_exp,$bord[1][$i],"mail");
				$fileAttachment[]=array(
					'content' => $ppp,
					'name' => $bord[0][$i],
					'mime' =>  'application/pdf'
				);
				
				$n=$n+1;
			}
		}
		
		/*echo "<pre>";
		print_r ($impo) ;
		echo "</pre>";
		die();
		*/
		if($impo){
			for($i=0;$i<count($impo[0]);$i++){
				//$l=($impo[1][$i]==Configuration::get('default_carrier_159357'))?'-1':$impo[1][$i];
				
				$fileAttachment[]=array(
					'content' => file_get_contents($this->xls($id_exp,$impo[1][$i])),
					'name' => $impo[0][$i],
					'mime' =>  'application/vnd.ms-excel'
				);
			}
		}
		
		//echo $ppp;
		//die();
		//echo "http://localhost/prestashop2/prestashop/admin492htr1wb/".$this->context->link->getAdminLink('AdminGenerationDocs').'&format=pdf&id_exp=1';
		/*echo $bord;
		die();
		*/
		$send = Mail::Send($id_land, $template_name, $title, $templateVars, $toMail, '', '', '', $fileAttachment, NULL, $mailDir);

		if ($send){
			Export::setMailSended($id_exp,'amana');
			Configuration::updateValue('nomination_159357',date("dmY").','.$n);
			
			$ords=Export::getExportOrders($id_exp);
			$order_state_id=(int)Configuration::get('state3_159357');
			foreach($ords as $ord){
				
				//$objOrder = new Order((object)array('id'=>'$id_ord')); //order with id=$_GET["action"]
				$objOrder = new Order($ord['id_order']);
				//$objOrder->getFields();
				//echo $id_ord."<br/>";
				//print_r($objOrder);
				//die();
				$objOrder->setCurrentState($order_state_id);
			}
			
			echo 'Done';
			
		}else{
			echo 'Error';
		}
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
	function cellColor($objPHPExcel,$cells,$color){
		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array(
				 'rgb' => $color
			)
		));
	}
	
	function pdf($id_exp,$zone_id,$typefile=null){
		$zone=Magasin::getZoneName($zone_id);
		$export=Export::getExport($id_exp);
		
		$myorders=Export::getOrdersByZone($id_exp,$this->context->language->id,$zone_id);
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Bordereau de dépôt');
		$pdf->SetSubject('Bordereau de dépôt');
		$pdf->SetKeywords('Bordereau de dépôt,Decathlon');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 033', PDF_HEADER_STRING);

		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		/*if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}*/

		// ---------------------------------------------------------

		// convert TTF font to TCPDF format and store it on the fonts folder
		$fontname = TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/../../libs/TCPDF/fonts/code_police.ttf', 'TrueTypeUnicode', '', 96);


		// set default font subsetting mode
		$pdf->setFontSubsetting(false);

		$pdf->AddPage('L', 'A4');
		
		//$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetFont('dejavusans', '', 10);
		$pdf->Ln(2);
		$tbl = "
		<table  border=\"1\">
			<tr nobr=\"true\">
				<td style=\"text-align: center;width:240px;\">Code à barre</td>
				<td style=\"text-align: center;width:140px;\">Nom du <br/>Destinataire</td>
				<td style=\"text-align: center;width:280px;\">Adresse</td>
				<td style=\"text-align: center;width:90px;\">Ville</td>
				<td style=\"text-align: center;width:65px;\">CRBT</td>
				<td style=\"text-align: center;width:65px;\">VD</td>
				<td style=\"text-align: center;width:75px;\">Observation</td>
			</tr>";
			foreach ($myorders as $row) {
				if($row['is_valid']==0)
					continue;
				$lcs=explode(',',$row['tracking_number']);
				foreach ($lcs as $field) {
					if($field)
					$tbl.= "
					<tr nobr=\"true\">
						<td style=\"text-align: center;\"><span style=\"font-family: code_police;font-size:30px;\">".$field."</span></td>
						<td style=\"text-align: center;\">".$row['firstname']." ".$row['lastname']."</td>
						<td>".$row['address1']." ".($row['address2']!=null?$row['address2']." ":"").$row['postcode']." ".$row['city']." "."</td>
						<td style=\"text-align: center;\">".$row['district']."</td>
						<td style=\"text-align: center;\">".(($this->ispaymentmtc($row['payment']))?(number_format(0,2,'.','')):(number_format($row['total_ttc'],2,'.','')))."</td>
						<td style=\"text-align: center;\">
						".((((int)$row['total_products_wt'])>((int)Configuration::get('seuil_garantie_159357')))?(number_format($row['total_products_wt'],2,'.','')):"")
						."</td>
						<td></td>
					</tr>";
				}
			}
		$tbl.=	"
		</table>
		";
		
		$pdf->writeHTML($tbl, true, false, false, false, '');
		
		// ---------------------------------------------------------
		
		//Close and output PDF document
		//$pdf->Output('libsexample_033.pdf', 'E');
		//$pdf->Output(dirname(__FILE__).'/../../files/libsexample_033.pdf', 'I');
		ob_end_clean();
		if($typefile=="mail")
			return $pdf->Output("", "S");
		else
			$pdf->Output('Bordereau_de_depot_'.$zone[0]['zone_name'].'_'.date("d-m-y", strtotime($export[0]['date_exp'])).'.pdf', 'I');
	}
	
	function etiquettes($id_exp){
		$expedition=Export::getExport($id_exp);
		$myorders=Export::getExportDetail($id_exp,$this->context->language->id);
		
		
		
		//$pageformat=Template::getActiveTemplate()[0];
		$params=json_decode(Configuration::get('template_page_159357'), true);
		
		// create new PDF document
		$pageLayout = array($params["width"], $params["height"]); //  or array($height, $width) 
		$pdf = new MYTICKETSPDF($params["orientation"], 'pt', $pageLayout, true, 'UTF-8', false);
		//$pdf = new MYTICKETSPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor(PDF_AUTHOR);
		$pdf->SetTitle('Liste des étiquettes');
		$pdf->SetSubject('Liste des étiquettes');
		$pdf->SetKeywords('Liste des étiquettes');

		// set default header data
		//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 033', PDF_HEADER_STRING);

		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins($params['margin-left'], $params['margin-top'], $params['margin-right']);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, $params['margin-bottom']);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		/*if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}*/

		// ---------------------------------------------------------
		/*$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'rtl';
		$lg['a_meta_language'] = 'fa';
		$lg['w_page'] = 'page';

		// set some language-dependent strings (optional)
		$pdf->setLanguageArray($lg);
        */
		//
		
		$pdf->SetFont('dejavusans', '', 12);

		// convert TTF font to TCPDF format and store it on the fonts folder
		$fontname = TCPDF_FONTS::addTTFfont(dirname(__FILE__).'/../../libs/TCPDF/fonts/code_police.ttf', 'TrueTypeUnicode', '', 96);


		// set default font subsetting mode
		$pdf->setFontSubsetting(false);
		
		
		$cols=1;
		
		$inc=0;
		$pdf->AddPage($params["orientation"], $pageLayout);
		//$tbl="<table style=\"background-color:yellow;width:100%;\" ><tr><td>AAA</td></tr></table>";
		$tbl ="<table style=\"width:100%;\" >";
		//BOUCLE
		$count_=0;
		
		foreach ($myorders as $row) {
			if($row['is_valid']==0)
								continue;
						$lcs=explode(',',$row['tracking_number']);
						$nbr=count(array_filter($lcs));
						$i=1;
						foreach ($lcs as $field) {
							if($field){
								$s="";
								if($count_==0)
									$s="<tr>";
								
								
								$store=$this->getStore($row);
								$store=Magasin::getMagasin($store[0]['id_store']);
								if(!$store)
								$store=Magasin::getDefaultMagasin();
							
								$date = new DateTime($expedition[0]['date_exp']);
								$_date=$date->format('d/m/Y');
								
								//$k="<table style=\"background-color:yellow;width:100%;\" ><tr><td>AAA</td></tr></table>";
								$k=$this->generateTicket($expedition[0]['date_exp'],$row,$pdf,$field,$i,$nbr,$pageformat);
								
								
								
								
								
								
								
								
								
								
								
								$s.="<td>".$k."</td>";
								
								$count_++;
								if($count_==$params["cols"]){
									$s.="</tr>";
									$count_=0;
								}
								$i++;
								$tbl.=$s;
							}
						}
			
		}
		$tbl.="</table>";
		
		//echo $tbl;
		//die();
		$pdf->writeHTML($tbl, true, false, false, false, '');
		// ---------------------------------------------------------
		ob_end_clean();
		//Close and output PDF document
		$pdf->Output(dirname('Etiquettes.pdf', 'S'));
		//$pdf->Output(dirname(__FILE__).'/../../files/libsexample_033.pdf', 'I');
		
	}
	public function getStore($order){
		$store=null;
		
		if(!$store){
			$store=Magasin::getMagasinByCdPostIN($order['postcode']);
		}
		if(!$store){
			$store=Magasin::getMagasinByCdPostCOM($order['postcode']);
		}
		if(!$store){
			$store=Magasin::getMagasinByCdPostBET($order['postcode']);
		}
		
		return $store;
	}
	public function generateTicket($_date,$order,$pdf,$lc,$num_tick,$nbr_tick,$pageformat){
		
		$store=$this->getStore($order);
		$store=Magasin::getMagasin($store[0]['id_store']);
		if(!$store)
		$store=Magasin::getDefaultMagasin();
	
		$date = new DateTime($_date);
		$_date=$date->format('d/m/Y');
		/*$tbl = $pageformat['htmlcode'];
		
		$pdf->writeHTML($tbl, true, false, false, false, '');*/
		//return "<table style=\"background-color:yellow;width:100%;\" ><tr><td>AAA</td></tr></table>";
		return  $this->getTicket($store,$_date,$order,$pdf,$lc,$num_tick,$nbr_tick,$pageformat);
		
	}
	public function getTicket($store,$_date,$order,$pdf,$lc,$num_tick,$nbr_tick,$pageformat){
		$s= htmlspecialchars_decode(Configuration::get('template_etiquette_159357'));
		$s=str_replace("#storeaddress1",$store[0]['address1'],$s);
		$s=str_replace("#storeaddress2",$store[0]['address2'],$s);
		$s=str_replace("#storecity",$store[0]['city'],$s);
		$s=str_replace("#storephone",$store[0]['phone'],$s);
		if($nbr_tick>1){
		$s=str_replace("#numticket",($num_tick."/".$nbr_tick),$s);
		}else{
		$s=str_replace("#numticket","",$s);
		}
		
		$s=str_replace("#order_id",$order['id_order'],$s);
		
		if(($nbr_tick>1 && $num_tick==1)  || $nbr_tick==1){ 
			//////sss
			if($this->ispaymentmtc($order['payment'])){
				$s=str_replace("#total_ttc",(number_format(0,2,'.','')),$s);
			}else{
				$s=str_replace("#total_ttc",(number_format($order['total_ttc'],2,'.','')),$s);
			}
		}else if($nbr_tick>1 && $num_tick>1){
			$s=str_replace("#total_ttc",("Voir colis 1/".$nbr_tick),$s);
		}
		
		$s=str_replace("#ccp_159357",Configuration::get('ccp_159357'),$s);
		$s=str_replace("#date_exp",$_date,$s);
		$s=str_replace("#firstname",$order['firstname'],$s);
		$s=str_replace("#lastname",$order['lastname'],$s);
		
		$s=str_replace("#address1",$order['address1'],$s);
		$s=str_replace("#address2",$order['address2'],$s);
		$s=str_replace("#district",$order['district'],$s);
		$s=str_replace("#postcode",$order['postcode'],$s);
		$s=str_replace("#phone_mobile",$order['phone_mobile'],$s);
		$s=str_replace("#city",$order['city'],$s);
		$s=str_replace("#lc",$lc,$s);
		
		if(((int)$order['total_products_wt'])>((int)Configuration::get('seuil_garantie_159357'))){
			if(($nbr_tick>1 && $num_tick==1)  || $nbr_tick==1){ 
				$s=str_replace("#valeur_dec",("Valeur déclarée :<br/>".number_format($order['total_products_wt'],2,'.','')),$s);
			}else{
				$s=str_replace("#valeur_dec",("Voir colis 1/".$nbr_tick),$s);
			}
		}else{
			$s=str_replace("#valeur_dec","",$s);
		}
		if(($nbr_tick>1 && $num_tick==1)  || $nbr_tick==1){ 
			$s=str_replace("#poids",(number_format($order['weight'],2,'.','')),$s);
		}else if($nbr_tick>1 && $num_tick>1){
			$s=str_replace("#poids","Voir colis 1/".$nbr_tick,$s);
		}
		
		$s=str_replace("#img_folder",dirname(__FILE__)."/../../images",$s);
		return $s;
	}
	function ispaymentmtc($payment){
		$ismtc=true;
		
		if (strpos($payment,"Maroc") === false) {
			$ismtc=false;
		}
		if (strpos($payment,"Telecommerce") === false) {
			$ismtc=false;
		}
		return $ismtc;
	}
}
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'amana.jpg';
		$poste = K_PATH_IMAGES.'decathlon.jpg';
		
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->ln(4);
		$this->Cell(0, 0, 'Bordereau de dépôt en nombre AMANA 131 ', 0, 2, 'C', 0, '', 0, false, 'M', 'M');
		$this->ln(2);
		$this->Cell(0, 0, 'Client : DECATHLON Maroc - Service Ecommerce', 0, 0, 'C', 0, '', 0, true, 'M', 'M');
		$this->Image($poste, 15, 7, 40, '', 'JPG', '', 'T');
		$this->Image($image_file, 240, 5, 40, '', 'JPG', '', 'T');
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		//$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$tDate=date('d/m/Y - H:i');
		$this->Cell(0, 0, $tDate, false, 0, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, true, 'C', 0, '', 0, false, 'T', 'M');
		
	}
}
class MYTICKETSPDF extends TCPDF {

	//Page header
	public function Header() {
		
	}

	// Page footer
	public function Footer() {
		
	}
}
		