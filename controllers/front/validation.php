<?php

/**
 * @since 1.5.0
 */
class Pesetacoin_ps_paymentValidationModuleFrontController extends ModuleFrontController
{
	public function postProcess()
	{
		$cart = $this->context->cart;

		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == 'pesetacoin_ps_payment')
			{
				$authorized = true;
				break;
			}

		if (!$authorized)
			die($this->module->l('El método de pago no está disponible.', mod='pesetacoin_ps_payment'));

		$customer = new Customer($cart->id_customer);

		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);


		global $currency;
		$my_currency_iso_code = $currency->iso_code;
		$my_currency_sign = $currency->sign;


		$mailVars = array( 
			'{direccion_ptc}' => Configuration::get('PTC_PAYMENT_DIR_PAGO'),
			'{importe_ptc}' => Configuration::get('PTC_PAYMENT_IMPORTE'),
			'{importe_ptc_ptc}' => Configuration::get('PTC_PAYMENT_IMPORTE_PTC'),
			'{currency_iso_code_ptc}' => $my_currency_iso_code,
			'{currency_sign_ptc}' => $my_currency_sign
		);
		
		Configuration::deleteByName('PTC_PAYMENT_IMPORTE');
		Configuration::deleteByName('PTC_PAYMENT_IMPORTE_PTC');
		

		$this->module->validateOrder((int)$cart->id, Configuration::get('PTC_PAYMENT_ID_ORDER_STATE'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
		Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
	}
}
