<?php
/*
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2016 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */


if (!defined('_PS_VERSION_'))
    exit;


include_once(_PS_MODULE_DIR_.'pesetacoin_ps_payment/model/pesetacoinpayment.php');

class Pesetacoin_ps_payment extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();
    
    public $extra_mail_vars;
    
    public function __construct()
    {
        $this->name             = 'pesetacoin_ps_payment';
        $this->tab              = 'payments_gateways';
        $this->version          = '1.0';
        $this->author           = 'marcos.trfn@gmail.com';
        $this->controllers      = array(
            'payment',
            'validation'
        );
        $this->is_eu_compatible = 1;
        
        $this->currencies      = true;
        $this->currencies_mode = 'checkbox';
		$this->direccion_ptc   = false;
       
/*
        $config = Configuration::getMultiple(array(
            'PESETACOIN_PS_PAYMENT_NAME',
            'PESETACOIN_PS_PAYMENT_ADDRESS'
        ));

        if (isset($config['PESETACOIN_PS_PAYMENT_NAME']))
            $this->pesetacoin_ps_paymentName = $config['PESETACOIN_PS_PAYMENT_NAME'];
        if (isset($config['PESETACOIN_PS_PAYMENT_ADDRESS']))
            $this->address = $config['PESETACOIN_PS_PAYMENT_ADDRESS'];
*/     		

/*
		// {direccion_ptc} deber ser una direccion de la tabla sin tener pedido asociado	
		if (!Configuration::get('PTC_PAYMENT_DIR_PAGO')) {
			$sql = "SELECT token_ptc FROM PREFIX_pesetacoin_ps_payment WHERE estado_ptc = 0 AND id_pedido_ptc = '0'";
			$mysql = $this->prepareSql($sql);
			$this->direccion_ptc = Db::getInstance()->getValue($mysql);
			Configuration::updateValue('PTC_PAYMENT_DIR_PAGO', $this->direccion_ptc);
		}
		*/
		
        $this->bootstrap = true;
        parent::__construct();
        
        $this->displayName            = $this->l('Pesetacoin Payment');
        $this->description            = $this->l('Descripcion de Pesetacoin Payment');
        $this->confirmUninstall       = $this->l('Are you sure you want to delete these details?');
        $this->ps_versions_compliancy = array(
            'min' => '1.6',
            'max' => '1.6.99.99'
        );
        
     
        if (!count(Currency::checkPaymentCurrencies($this->id)))
            $this->warning = $this->l('No currency has been set for this module.');
		
	/* valores de configuracion */
	if (!Configuration::get('PTC_PAYMENT_ID_ORDER_STATE')) {
		$this->warning = $this->l('Debe seleccionar un id de estado.');
	}		

		
    }
    
    public function install()
    {

	  // Call install parent method
	  if (!parent::install())
		return false;

	  // Execute module install SQL statements
	  $sql_file = dirname(__FILE__).'/install/install.sql';
	  if (!$this->loadSQLFile($sql_file))
		return false;

	  // Register hooks
	  if (!$this->registerHook('payment'))
		return false;
	
	  if (!$this->registerHook('displayPaymentEU'))
		return false;
   
           if (!$this->registerHook('paymentReturn'))
		return false;
	
	  // Preset configuration values
	  Configuration::updateValue('PTC_PAYMENT_DIR', '00000000000000000000000000');

	  // All went well!
	  return true;

    }
    
    public function uninstall()
    {

	  // Call uninstall parent method
	  if (!parent::uninstall())
		return false;

	  // Execute module install SQL statements
	  $sql_file = dirname(__FILE__).'/install/uninstall.sql';
	  if (!$this->loadSQLFile($sql_file))
	    return false;

	  // Delete configuration values
	  Configuration::deleteByName('PTC_PAYMENT_DIR');
	  Configuration::deleteByName('PTC_PAYMENT_ID_ORDER_STATE');
	  
	  // All went well!
	  return true;

    }
    
    private function _postValidation1()
    {
        if (Tools::isSubmit('submit'.$this->name)) {
            if (!Tools::getValue('PTC_PAYMENT_ID_ORDER_STATE')) {
                $this->_postErrors[] = $this->l('The "Order State" field is required.');
			}
		}
    }
    

    private function _postValidation2()
    {
        if (Tools::isSubmit('submit2'.$this->name)) {
			if (!Tools::getValue('PTC_PAYMENT_DIR')) {
                $this->_postErrors[] = $this->l('The "Direccion" field is required.');
			}else{
				// control de la direccion de pago PTC_PAYMENT_DIR	
				// Usar el api de Xaxuke 
				// http://nodos.pesetacoin.info/api/validador.php?direccion=
				// responde con un json
				// {"status" : "success" , "direccion" : "dfadfas", "respuesta" : "incorrecta"}
				// {"status" : "success" , "direccion" : "LCK7f4n6NnCuPfmC7yqQskAeMLFfFNqzjZ", "respuesta" : "correcta"}
					
				$token_ptc = Tools::getValue('PTC_PAYMENT_DIR');
				$sql = "SELECT COUNT(*) FROM PREFIX_pesetacoin_ps_payment WHERE token_ptc='{$token_ptc}'";
				$totalToken= Db::getInstance()->getValue($this->prepareSql($sql));
				if ($totalToken==0) {
					Db::getInstance()->insert('pesetacoin_ps_payment', array(
						'token_ptc' => $token_ptc,
						'estado_ptc' => (int)0,
						'id_pedido_ptc' => '0',
						'date_add' => date_create()->format('Y-m-d H:i:s')
					));
				}else{
				   $this->_postErrors[] = $this->l('La "Direccion de Pago" ya existe en la base de datos.');
				}
			}


        }
    }



    private function _postProcess()
    {
        if (Tools::isSubmit('submit1'.$this->name) &&  Tools::getValue('PTC_PAYMENT_ID_ORDER_STATE')) {
			Configuration::updateValue('PTC_PAYMENT_ID_ORDER_STATE', Tools::getValue('PTC_PAYMENT_ID_ORDER_STATE'));
			$this->_html .= $this->displayConfirmation($this->l('Id Estado Actualizado'));
		}
        if (Tools::isSubmit('submit2'.$this->name) &&  Tools::getValue('PTC_PAYMENT_DIR')) {
			Configuration::updateValue('PTC_PAYMENT_DIR', Tools::getValue('PTC_PAYMENT_DIR'));
			$this->_html .= $this->displayConfirmation($this->l('Dirección creada'));
        }
		

        
    }
    
    private function _displayPesetacoin()
    {
        return $this->display(__FILE__, 'infos.tpl');
    }
    



    public function getContent()
    {
        $this->_html = '';
        
        if (Tools::isSubmit('submit1'.$this->name)) {
            $this->_postValidation1();
            if (!count($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors as $err)
                    $this->_html .= $this->displayError($err);
        }

        if (Tools::isSubmit('submit2'.$this->name)) {
            $this->_postValidation2();
            if (!count($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors as $err)
                    $this->_html .= $this->displayError($err);
        }
		
		
        

        /* html propio */
		/* aqui tenemos que crear una tabla con las direcciones usadas, libres, etc */
		
		$sql = "SELECT * FROM PREFIX_pesetacoin_ps_payment WHERE estado_ptc=0";
		$direcciones_pendientes= Db::getInstance()->ExecuteS($this->prepareSql($sql));
		
		$sql = "SELECT * FROM PREFIX_pesetacoin_ps_payment WHERE estado_ptc=1";
		$direcciones_pedido = Db::getInstance()->ExecuteS($this->prepareSql($sql));
		$token = Tools::getAdminTokenLite('AdminModules');
		
		
		// $tokenProducts = Tools::getAdminToken('AdminProducts'.intval(Tab::getIdFromClassName('AdminProducts')).intval($cookie->id_employee));

		$tokenOrder = Tools::getAdminToken('AdminOrders'.intval(Tab::getIdFromClassName('AdminOrders')).intval($cookie->id_employee));
		
		$token = Tools::getAdminToken('AdminModules'.intval(Tab::getIdFromClassName('AdminModules')).intval($cookie->id_employee));
		
		/* acceso a un pedido  http://localhost/pesetacoin/prestashop16/admin905ofjgxk/index.php?controller=AdminOrders&id_order=24&vieworder&token=e8d6dcf379c452f3cd1eb43842537c86 
		
		<td class="pointer" onclick="document.location = 'index.php?controller=AdminOrders&amp;id_order=21&amp;vieworder&amp;token=e8d6dcf379c452f3cd1eb43842537c86'">
				
		*/
		
        $this->context->smarty->assign(array(
			'direcciones_pendientes' => $direcciones_pendientes,
			'direcciones_pedido' => $direcciones_pedido,
			'token' => $token
			
		));		
		$custom_tpl = $this->display(__FILE__, '/views/templates/hook/custom.tpl');

        $this->_html .= $this->_displayPesetacoin();
        $this->_html .= $this->renderForm1();
		$this->_html .= $this->renderForm2();
        $this->_html .= $custom_tpl;
        
        return $this->_html;
    }




    
    public function hookPayment($params)
    {
        if (!$this->active)
            return;
        if (!$this->checkCurrency($params['cart']))
            return;
        
        $this->smarty->assign(array(
            'this_path' => $this->_path,
            'this_path_pesetacoin_ps_payment' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/'
        ));
        return $this->display(__FILE__, 'payment.tpl');
    }
    
    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active)
            return;
        if (!$this->checkCurrency($params['cart']))
            return;
        
        $payment_options = array(
            'cta_text' => $this->l('Pay by Pesetacoin'),
            'logo' => Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/pesetacoin_ps_payment.png'),
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true)
        );
        
        return $payment_options;
    }
    
    public function hookPaymentReturn($params)
    {
        if (!$this->active)
            return;
        
        $state = $params['objOrder']->getCurrentState();
        if (in_array($state, array(
            Configuration::get('PTC_PAYMENT_ID_ORDER_STATE'),
            Configuration::get('PS_OS_OUTOFSTOCK'),
            Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')
        ))) {
            $this->smarty->assign(array(
                'status' => 'ok',
                'id_order' => $params['objOrder']->id
            ));
            if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference)) {
                $this->smarty->assign('reference', $params['objOrder']->reference);				
				// insertar numero de referencia en base de datos
				$token = Configuration::get('PTC_PAYMENT_DIR_PAGO');
				// $token = $this->direccion_ptc;
				$sql = "UPDATE PREFIX_pesetacoin_ps_payment SET estado_ptc=1, id_pedido_ptc='{$params['objOrder']->reference}' WHERE token_ptc='{$token}'";
				$mysql = $this->prepareSql($sql);
				Db::getInstance()->execute($mysql);	
				// Configuration::deleteByName('PTC_PAYMENT_DIR_PAGO');				
			    // $this->smarty->assign('reference', $mysql);			
			}
        } else
            $this->smarty->assign('status', 'failed');
        return $this->display(__FILE__, 'payment_return.tpl');
    }
    
    public function checkCurrency($cart)
    {
        $currency_order    = new Currency((int) ($cart->id_currency));
        $currencies_module = $this->getCurrency((int) $cart->id_currency);
        
        if (is_array($currencies_module))
            foreach ($currencies_module as $currency_module)
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
        return false;
    }
    
    public function renderForm1()
    {
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		 
		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Estado'),
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Id Estado'),
					'name' => 'PTC_PAYMENT_ID_ORDER_STATE',
					'size' => 4,
					'required' => true,
					'desc'     => $this->l('Introduzca el id del estado creado para espera de pago con pesetacoin')
				)
			),
			'submit' => array(
				'title' => $this->l('Guardar'),
				'class' => 'btn btn-default pull-right'
			)
		);

		 
		$helper = new HelperForm();
		 
		// Module, token and currentIndex
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
		$helper->submit_action = 'submit1'.$this->name;
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
		$helper->fields_value['PTC_PAYMENT_ID_ORDER_STATE'] = Configuration::get('PTC_PAYMENT_ID_ORDER_STATE');
		// $helper->fields_value['PTC_PAYMENT_DIR'] = Configuration::get('PTC_PAYMENT_DIR');
		 
		return $helper->generateForm($fields_form);

    }
   

   
       public function renderForm2()
    {
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Direcciones'),
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Dirección de pago'),
					'name' => 'PTC_PAYMENT_DIR',
					'size' => 60,
					'required' => true,
					'desc'     => $this->l('Introduzca una dirección de pago generada en su monedero de pesetaCoin')
				)		
			),
			'submit' => array(
				'title' => $this->l('Guardar'),
				'class' => 'btn btn-default pull-right'
			)
		);

		 
		$helper = new HelperForm();
		 
		// Module, token and currentIndex
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
		$helper->submit_action = 'submit2'.$this->name;
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
		// $helper->fields_value['PTC_PAYMENT_ID_ORDER_STATE'] = Configuration::get('PTC_PAYMENT_ID_ORDER_STATE');
		$helper->fields_value['PTC_PAYMENT_DIR'] = Configuration::get('PTC_PAYMENT_DIR');
		 
		return $helper->generateForm($fields_form);

    }

	

	
	public function prepareSql($sql)
	{
	  // Get install SQL file content
	  $sql_content = $sql;

	  // Replace prefix and store SQL command in array
	  $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
	  
	  
	  return $sql_content;
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



}
