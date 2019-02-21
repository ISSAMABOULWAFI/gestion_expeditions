<?php 

if(!defined('_PS_VERSION_'))
	exit;

require_once dirname(__FILE__).'/models/MagasinModel.php';

class gestion_expeditions extends Module
{
	
	//Definition des menus 
	protected $tabs = [
        [
            'name'      => 'Etiquettes',
            'className' => 'AdminExp',
            'active'    => 1,
            //submenus
            'childs'    => [
				[
                    'active'    => 1,
                    'name'      => 'Gestion des expéditions',
                    'className' => 'AdminExp',
                ],
                [
                    'active'    => 1,
                    'name'      => 'Gestion des magasins/codes postaux',
                    'className' => 'AdminMag',
                ],
				[
					'name'      => 'Docs',
					'className' => 'AdminGenerationDocs',
					'active'    => 1,
				],
            ],
        ],
    ];
	
		
	//Le constructeur du module
	public function __construct(){
		$this->name=$this->l('gestion_expeditions');
		$this->displayName=$this->l('Gestion des expéditions');
		$this->version='1.0.1';
		$this->description=$this->l('Module pour la gestion des expéditions Amana');
		$this->ps_versions_compliancy=['min'=>'1.6','max'=>_PS_VERSION_];
		$this->author='ERRAMY NOUREDDINE';
		$this->tab='administration';
		$this->bootstrap=true;
		parent::__construct();
	}
	//La fonction qui gère l'affichage de la page des configuration
	public function getContent(){
		$ok=true;
		$output = null;
		if (Tools::isSubmit('submit'.$this->name)){
			//print_r($_POST);
			//die();
			if(!$this->valide(strval(Tools::getValue('last_lc_159357')),'INT')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('prefix_lc_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('sufix_lc_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('ccp_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('seuil_garantie_159357')),'INT')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('nomination_bord_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('nomination_import_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('amana_mails_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('amana_mails_cc_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('amana_mail_objet_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('amana_mail_message_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			if(!$this->valide(strval(Tools::getValue('amana_code_produit_159357')),'STRING')){
				$ok=false;
				$output .= $this->displayError('Erreur!');
			}
			
			if($ok){
				Configuration::updateValue('last_lc_159357', strval(Tools::getValue('last_lc_159357')));
				Configuration::updateValue('prefix_lc_159357', strval(Tools::getValue('prefix_lc_159357')));
				Configuration::updateValue('sufix_lc_159357', strval(Tools::getValue('sufix_lc_159357')));
				Configuration::updateValue('ccp_159357', strval(Tools::getValue('ccp_159357')));
				Configuration::updateValue('seuil_garantie_159357', strval(Tools::getValue('seuil_garantie_159357')));
				Configuration::updateValue('nomination_bord_159357', strval(Tools::getValue('nomination_bord_159357')));
				Configuration::updateValue('nomination_import_159357', strval(Tools::getValue('nomination_import_159357')));
				Configuration::updateValue('amana_mails_159357', strval(Tools::getValue('amana_mails_159357')));
				Configuration::updateValue('amana_mails_cc_159357', strval(Tools::getValue('amana_mails_cc_159357')));
				Configuration::updateValue('amana_mail_objet_159357', strval(Tools::getValue('amana_mail_objet_159357')));
				Configuration::updateValue('amana_mail_message_159357', htmlspecialchars(strval(Tools::getValue('amana_mail_message_159357'))));
				Configuration::updateValue('amana_code_produit_159357', strval(Tools::getValue('amana_code_produit_159357')));
				//Configuration::updateValue('client_mail_objet_159357', strval(Tools::getValue('client_mail_objet_159357')));
				//Configuration::updateValue('client_mail_message_159357', htmlspecialchars(strval(Tools::getValue('client_mail_message_159357'))));
				Configuration::updateValue('state1_159357', strval(Tools::getValue('state1_159357')));
				Configuration::updateValue('state2_159357', strval(Tools::getValue('state2_159357')));
				Configuration::updateValue('state3_159357', strval(Tools::getValue('state3_159357')));
				Configuration::updateValue('template_page_159357', strval(Tools::getValue('template_page_159357')));
				Configuration::updateValue('template_etiquette_159357', htmlspecialchars(strval(Tools::getValue('template_etiquette_159357'))));
				$output .= $this->displayConfirmation('Settings updated');
			}
		}
		return $output.$this->displayForm();
	}
	public function displayForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		
		$res=Magasin::getAllStates($default_lang);
		$def1=Configuration::get('state1_159357');
		$def2=Configuration::get('state2_159357');
		$def3=Configuration::get('state3_159357');
		$default1=null;
		$default2=null;
		$default3=null;
		$options1=null;
		$options2=null;
		$options3=null;
		
		foreach($res as $row){
			
			if($def1==$row['id_order_state']){
				
				$default1=array(
					'value' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'label' => $row['name']   ,
					
				  );
			}else{
				$options1[]=array(
					'id_option' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'name' => $row['name']   ,
					
				  );
			}
			
			
			if($def2==$row['id_order_state']){
				
				$default2=array(
					'value' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'label' => $row['name']   ,
					
				  );
			}else{
				$options2[]=array(
					'id_option' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'name' => $row['name']   ,
					
				  );
			}
			
			if($def3==$row['id_order_state']){
				
				$default3=array(
					'value' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'label' => $row['name']   ,
					
				  );
			}else{
				$options3[]=array(
					'id_option' => $row['id_order_state'],                 // The value of the 'value' attribute of the <option> tag.
					'name' => $row['name']   ,
					
				  );
			}
		}
		
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
			),
			
			
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Dernier LC'),
					'name' => 'last_lc_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('LC Préfixe'),
					'name' => 'prefix_lc_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('LC Suffixe'),
					'name' => 'sufix_lc_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('CCP'),
					'name' => 'ccp_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Seuil de garantie'),
					'name' => 'seuil_garantie_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Nomination du bordereau'),
					'name' => 'nomination_bord_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Nomination d\'import en masse'),
					'name' => 'nomination_import_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Amana mails'),
					'name' => 'amana_mails_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Mails CC'),
					'name' => 'amana_mails_cc_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Mail AMANA Objet'),
					'name' => 'amana_mail_objet_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Mail AMANA Message'),
					'name' => 'amana_mail_message_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'text',
					'label' => $this->l('Code Produit AMANA'),
					'name' => 'amana_code_produit_159357',
					'size' => 20,
					'required' => false
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Template Page Parameters'),
					'name' => 'template_page_159357',
					'required' => false
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Template etiquette code'),
					'name' => 'template_etiquette_159357',
					'required' => false
				),
				array(
				  'type' => 'select',                              
				  'label' => $this->l('Status des commandes à expédier'),         
				  'desc' => $this->l(''),
				  'name' => 'state1_159357',
				  'required' => false,                            
				  'options' => array(
					'query' => $options1,                         
					'id' => 'id_option',                         
					'name' => 'name',
					'default' => $default1					
				  )
				),
				array(
				  'type' => 'select',                              
				  'label' => $this->l('Status des commandes expédiées'),         
				  'desc' => $this->l(''),
				  'name' => 'state2_159357',
				  'required' => false,                            
				  'options' => array(
					'query' => $options2,                         
					'id' => 'id_option',                         
					'name' => 'name',
					'default' => $default2					
				  )
				),
				array(
				  'type' => 'select',                              
				  'label' => $this->l('Status des commandes après envoi du mail'),         
				  'desc' => $this->l(''),
				  'name' => 'state3_159357',
				  'required' => false,                            
				  'options' => array(
					'query' => $options3,                         
					'id' => 'id_option',                         
					'name' => 'name',
					'default' => $default3					
				  )
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			)
		);
		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		 
		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
				'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);
		 
		// Load current value
		$helper->fields_value['last_lc_159357'] = Configuration::get('last_lc_159357');
		$helper->fields_value['prefix_lc_159357'] = Configuration::get('prefix_lc_159357');
		$helper->fields_value['sufix_lc_159357'] = Configuration::get('sufix_lc_159357');
		$helper->fields_value['ccp_159357'] = Configuration::get('ccp_159357');
		$helper->fields_value['seuil_garantie_159357'] = Configuration::get('seuil_garantie_159357');
		$helper->fields_value['nomination_bord_159357'] = Configuration::get('nomination_bord_159357');
		$helper->fields_value['nomination_import_159357'] = Configuration::get('nomination_import_159357');
		$helper->fields_value['amana_mails_159357'] = Configuration::get('amana_mails_159357');
		$helper->fields_value['amana_mails_cc_159357'] = Configuration::get('amana_mails_cc_159357');
		$helper->fields_value['amana_mail_objet_159357'] = Configuration::get('amana_mail_objet_159357');
		$helper->fields_value['amana_mail_message_159357'] = htmlspecialchars_decode(Configuration::get('amana_mail_message_159357'));
		$helper->fields_value['amana_code_produit_159357'] = Configuration::get('amana_code_produit_159357');
		//$helper->fields_value['client_mail_objet_159357'] = Configuration::get('client_mail_objet_159357');
		//$helper->fields_value['client_mail_message_159357'] = htmlspecialchars_decode(Configuration::get('client_mail_message_159357'));
		$helper->fields_value['state1_159357'] = Configuration::get('state1_159357');
		$helper->fields_value['state2_159357'] = Configuration::get('state2_159357');
		$helper->fields_value['state3_159357'] = Configuration::get('state3_159357');
		$helper->fields_value['template_page_159357'] = Configuration::get('template_page_159357');
		$helper->fields_value['template_etiquette_159357'] = htmlspecialchars_decode(Configuration::get('template_etiquette_159357'));
		
		 
		return $helper->generateForm($fields_form);
	}
	public function valide($varname,$type){
		//$v = strval(Tools::getValue($varname));
		//if (!$varname)
			//return false;
		
			//return false;
		if(!empty($varname))
		if($type=="INT"){
			$s=Validate::isINT($varname);
			if(!$s)
				return false;
		}
		return true;
	}
	
	
	public function loadSQLFile($sql_file)
	{
	  // Get install SQL file content
	  $sql_content = file_get_contents($sql_file);

	  // Replace prefix and store SQL command in array
	  $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
	  $sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

	  // Execute each SQL statement
	  $result = true;
	  foreach($sql_requests as $request)
	  if (!empty($request))
		$result &= Db::getInstance()->execute(trim($request));

	  // Return result
	  return $result;
	}
	
	
	public function install() {
		
		if(!parent::install())
			return false;
		$this->registerHook('displayBackOfficeHeader');
		$this->registerHook('actionAdminControllerSetMedia');
		
		$sql_file = dirname(__FILE__).'/install/install.sql';
		if (!$this->loadSQLFile($sql_file))
			return false;	
		$s="&lt;table&gt; &lt;tr nobr=&quot;true&quot;&gt; &lt;td style=&quot;width:10px;&quot;&gt; &lt;/td&gt; &lt;td&gt; &lt;div style=&quot;width:10px;&quot;&gt;&amp;nbsp;&lt;br/&gt;&lt;br/&gt;&lt;br/&gt;&lt;/div&gt; &lt;table border=&quot;0.5&quot; cellpadding=&quot;2&quot;&gt; &lt;tr&gt; &lt;td style=&quot;width:300px;&quot; rowspan=&quot;3&quot; &gt; &lt;div style=&quot;font-size:13px;&quot;&gt;&lt;b&gt;Exp&eacute;diteur&lt;/b&gt;&lt;/div&gt; &lt;div style=&quot;width:100%;text-align:center;&quot;&gt;&lt;img style=&quot;width:160px;&quot; src=&quot;#img_folder/logo.png&quot; /&gt;&lt;/div&gt; &lt;div&gt; &lt;b style=&quot;font-size:13px;&quot;&gt;Decathlon / Service Ecommerce&lt;br/&gt;&lt;/b&gt; &lt;font style=&quot;font-size:13px;&quot;&gt;#storeaddress1 #storeaddress2 &lt;br&gt;#storecity &lt;br/&gt;&lt;b&gt;T&eacute;l:&lt;/b&gt; #storephone&lt;/font&gt; &lt;/div&gt; &lt;/td&gt; &lt;td style=&quot;&quot; colspan=&quot;2&quot; style=&quot;text-align:center;width:200px;&quot;&gt; &lt;div&gt;&lt;br/&gt;&lt;br/&gt;&lt;img style=&quot;width:120px;&quot; src=&quot;#img_folder/amana.jpg&quot; /&gt;&lt;br/&gt;&lt;/div&gt; &lt;/td&gt; &lt;td style=&quot;text-align:center;width:150px;&quot;&gt; &lt;div style=&quot;font-size:5pt&quot;&gt;&amp;nbsp;&lt;/div&gt; &lt;b style=&quot;font-size:30pt;&quot;&gt;#numticket&lt;/b&gt; &lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td colspan=&quot;2&quot; style=&quot;text-align:center;&quot;&gt; &lt;div style=&quot;font-size:5pt&quot;&gt;&amp;nbsp;&lt;/div&gt; &lt;font style=&quot;font-size:13px;&quot;&gt;&lt;b&gt;CRBT&lt;/b&gt;&lt;/font&gt; &lt;/td&gt; &lt;td style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;Commande N&deg;:&lt;br/&gt;#order_id&lt;/font&gt; &lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td style=&quot;text-align:center;&quot;&gt; &lt;b style=&quot;font-size:15px;&quot;&gt; #total_ttc &lt;/b&gt; &lt;/td&gt; &lt;td style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;CCP N&deg;&lt;br/&gt; #ccp_159357&lt;/font&gt; &lt;/td&gt; &lt;td style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;Date d'exp&eacute;dition &lt;br/&gt;#date_exp&lt;/font&gt; &lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td rowspan=&quot;3&quot; &gt; &lt;div style=&quot;font-size:13px;&quot;&gt;&lt;b&gt;Destinataire&lt;/b&gt;&lt;/div&gt; &lt;table&gt; &lt;tr&gt; &lt;td style=&quot;width:95px;font-size:12px;&quot;&gt;Nom et pr&eacute;nom&lt;/td&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;#firstname #lastname&lt;/font&gt;&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;Adresse&lt;/font&gt;&lt;/td&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;#address1 #address2 #district&lt;/font&gt;&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;T&eacute;l&lt;/font&gt;&lt;/td&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;#phone_mobile&lt;/font&gt;&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;Ville&lt;/font&gt;&lt;/td&gt; &lt;td&gt;&lt;font style=&quot;font-size:12px;&quot;&gt;&lt;b&gt;#city&lt;/b&gt;&lt;/font&gt;&lt;/td&gt; &lt;/tr&gt; &lt;/table&gt; &lt;/td&gt; &lt;td colspan=&quot;3&quot;&gt; &lt;div style=&quot;font-size:6pt&quot;&gt;&amp;nbsp;&lt;/div&gt; &lt;div style=&quot;text-align:center;font-family: code_police;font-size:38px;&quot;&gt;*#lc*&lt;/div&gt; &lt;div style=&quot;font-size:2pt&quot;&gt;&amp;nbsp;&lt;/div&gt; &lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td colspan=&quot;3&quot; style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;&lt;b&gt;CAB&lt;/b&gt;&lt;/font&gt; &lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td colspan=&quot;2&quot; style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;#valeur_dec&lt;/font&gt; &lt;/td&gt; &lt;td style=&quot;text-align:center;&quot;&gt; &lt;font style=&quot;font-size:12px;&quot;&gt;Poids : &lt;br/&gt; #poids&lt;/font&gt; &lt;/td&gt; &lt;/tr&gt; &lt;/table&gt; &lt;div style=&quot;width:10px;&quot;&gt;&lt;br/&gt;&lt;br/&gt;&lt;/div&gt; &lt;/td&gt; &lt;/tr&gt; &lt;/table&gt;";
		Configuration::updateValue('template_etiquette_159357', $s);
		Configuration::updateValue('template_page_159357', '{"orientation":"P","width":595,"height":842,"margin-left":25,"margin-right":5,"margin-bottom":50,"margin-top":0,"cols":1}');
		
		Configuration::updateValue('state1_159357', '3');
		Configuration::updateValue('state2_159357', '4');
		Configuration::updateValue('amana_mails_159357', '"noureddine.erramy@decathlon.com","adil.fettouch@decathlon.com"');
		Configuration::updateValue('amana_code_produit_159357', '15');
		Configuration::updateValue('last_lc_159357', '13312903');
		Configuration::updateValue('prefix_lc_159357', 'LC');
		Configuration::updateValue('sufix_lc_159357', 'MA');
		Configuration::updateValue('ccp_159357', '7557390');
		Configuration::updateValue('seuil_garantie_159357', '4000');
		Configuration::updateValue('nomination_bord_159357', 'Bordereau_#d/#m/#Y_#n');
		Configuration::updateValue('nomination_import_159357', 'ImpotEnMasse_#d/#m/#Y_#n');
		
		
		Configuration::updateValue('amana_mail_objet_159357', '[Decathlon] Expédition du #d/#m/#Y');
		$m='Bonjour,&#x3C;br/&#x3E;

		Veuillez Trouvez ci-joint les bordereaux et les import en masse de l&#x27;exp&#xE9;dition Decathlon du &#x3C;b&#x3E; #d/#m/#Y&#x3C;/b&#x3E;. 

		Et voici la liste des codes &#xE0; barres des colis volumineux : 
		#Volumineux

		Et ceux des commandes multi-colis :
		#Encombrants';
		Configuration::updateValue('amana_mail_message_159357', $m);
		
		
		$this->addTab($this->tabs);
		
		
		
		
		return true;
	}
	public function uninstall(){
		
		$sql_file = dirname(__FILE__).'/install/uninstall.sql';
	    if (!$this->loadSQLFile($sql_file))
			return false;

		
        $this->removeTab($this->tabs);
		
		
		
		Configuration::deleteByName('last_lc_159357');
		Configuration::deleteByName('seuil_garantie_159357');
		Configuration::deleteByName('prefix_lc_159357');
		Configuration::deleteByName('sufix_lc_159357');
		Configuration::deleteByName('ccp_159357');
		Configuration::deleteByName('nomination_bord_159357');
		Configuration::deleteByName('nomination_import_159357');
		Configuration::deleteByName('amana_mails_159357');
		Configuration::deleteByName('amana_mails_cc_159357');
		Configuration::deleteByName('amana_mail_objet_159357');
		Configuration::deleteByName('amana_mail_message_159357');
		Configuration::deleteByName('amana_code_produit_159357');
		//Configuration::deleteByName('client_mail_objet_159357');
		//Configuration::deleteByName('client_mail_message_159357');
		Configuration::deleteByName('state1_159357');
		Configuration::deleteByName('state2_159357');
		Configuration::deleteByName('template_page_159357');
		Configuration::deleteByName('template_etiquette_159357');
		
		$this->unregisterHook('displayBackOfficeHeader');
		$this->unregisterHook('actionAdminControllerSetMedia');
		
        return parent::uninstall();
    }
		
    public function addTab(
        $tabs,
        $id_parent = 0
    )
    {
        foreach ($tabs as $tab)
        {
            $tabModel             = new Tab();
            $tabModel->module     = $this->name;
            $tabModel->active     = $tab['active'];
            $tabModel->class_name = $tab['className'];
            $tabModel->id_parent  = $id_parent;

            //tab text in each language
            foreach (Language::getLanguages(true) as $lang)
            {
                $tabModel->name[$lang['id_lang']] = $tab['name'];
            }

            $tabModel->add();

            //submenus of the tab
            if (isset($tab['childs']) && is_array($tab['childs']))
            {
                $this->addTab($tab['childs'], Tab::getIdFromClassName($tab['className']));
            }
        }
        return true;
    }

    public function removeTab($tabs)
    {
        foreach ($tabs as $tab)
        {
            $id_tab = (int) Tab::getIdFromClassName($tab["className"]);
            if ($id_tab)
            {
                $tabModel = new Tab($id_tab);
                $tabModel->delete();
            }

            if (isset($tab["childs"]) && is_array($tab["childs"]))
            {
                $this->removeTab($tab["childs"]);
            }
        }

        return true;
    }
	public function hookDisplayBackOfficeHeader()
	{
	   $this->context->controller->addCss($this->_path.'css/tab.css');
	}
	
	public function hookActionAdminControllerSetMedia($params)
	{
		if ($this->context->controller->controller_name == 'AdminMag'){ 
			$this->context->controller->addCSS(($this->_path) . 'css/bootstrap-tagsinput.css');	
			$this->context->controller->addJS(($this->_path) . 'js/bootstrap-checkbox.js');			
			$this->context->controller->addJS(($this->_path) . 'js/bootstrap-tagsinput.min.js');
			

		}
    }
	
}