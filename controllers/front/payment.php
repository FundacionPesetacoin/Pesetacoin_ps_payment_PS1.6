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

/**
 * @since 1.5.0
 */
class Pesetacoin_ps_paymentPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
	public $display_column_left = false;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		$cart = $this->context->cart;
		if (!$this->module->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');


        	
		$obj_pesetacoin = new PesetaCoinPaymentFunciones();
		$getPriceEur = $obj_pesetacoin->getPriceEur();
		$getPriceUsd = $obj_pesetacoin->getPriceUsd();
		$getPriceBtc = $obj_pesetacoin->getPriceBtc();
		
		
		if ($getPriceEur=='--') {
			// tratar error
			
		}
		
                $importe = $cart->getOrderTotal(true, Cart::BOTH);
		$importePtc = $importe / $getPriceEur;

		// $direccion = Configuration::get('PTC_PAYMENT_DIR');

		$sql = "SELECT COUNT(token_ptc) FROM PREFIX_pesetacoin_ps_payment WHERE estado_ptc = 0 AND id_pedido_ptc = '0'";
		$mysql = $this->prepareSql($sql);
		$numero_direciones = Db::getInstance()->getValue($mysql);

		$sql = "SELECT token_ptc FROM PREFIX_pesetacoin_ps_payment WHERE estado_ptc = 0 AND id_pedido_ptc = '0'";
		$mysql = $this->prepareSql($sql);
		$direccion_pago = Db::getInstance()->getValue($mysql);
		Configuration::updateValue('PTC_PAYMENT_DIR_PAGO', $direccion_pago); 
			
			
		Configuration::updateValue('PTC_PAYMENT_IMPORTE_PTC', $importePtc);
                Configuration::updateValue('PTC_PAYMENT_IMPORTE', $importe);

		
        $this->context->smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),

                        'numero_direciones' => $numero_direciones,
			'importePtc' => $importePtc,
			'direccion' => $direccion_pago, // $direccion,
			
			'cust_currency' => $cart->id_currency,
			'currencies' => $this->module->getCurrency((int)$cart->id_currency),
			'total' => $cart->getOrderTotal(true, Cart::BOTH),
			'isoCode' => $this->context->language->iso_code,
			'this_path' => $this->module->getPathUri(),
			'this_path_pesetacoin_ps_payment' => $this->module->getPathUri(),
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->module->name.'/'
		));
		
		
		
		

		$this->setTemplate('payment_execution.tpl');
	}
	
	
	public function prepareSql($sql)
	{
	  // Get install SQL file content
	  $sql_content = $sql;

	  // Replace prefix and store SQL command in array
	  $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
	  
	  
	  return $sql_content;
	}
	
}
