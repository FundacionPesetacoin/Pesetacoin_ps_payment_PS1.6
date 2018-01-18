<?php

/**
 * @deprecated 1.5.0 This file is deprecated, use moduleFrontController instead
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
Tools::displayFileAsDeprecated();

include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/pesetacoin_ps_payment.php');

$context = Context::getContext();
$cart = $context->cart;
$pesetacoin_ps_payment = new Pesetacoin_ps_payment();

if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$pesetacoin_ps_payment->active)
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
	die($pesetacoin_ps_payment->l('El método de pago no está disponible.', 'validation'));

$customer = new Customer($cart->id_customer);

if (!Validate::isLoadedObject($customer))
	Tools::redirect('index.php?controller=order&step=1');

$currency = $context->currency;
$total = (float)$cart->getOrderTotal(true, Cart::BOTH);

$pesetacoin_ps_payment->validateOrder((int)$cart->id, Configuration::get('PS_OS_PESETACOIN_PS_PAYMENT'), $total, $pesetacoin_ps_payment->displayName, NULL, array(), (int)$currency->id, false, $customer->secure_key);

Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)($cart->id).'&id_module='.(int)($pesetacoin_ps_payment->id).'&id_order='.$pesetacoin_ps_payment->currentOrder.'&key='.$customer->secure_key);


