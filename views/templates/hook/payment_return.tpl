{*
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
*}

{if $status == 'ok'}
	<p>{l s='Your order on %s is complete.' sprintf=$shop_name mod='pesetacoin_ps_payment'}
		
		{if !isset($reference)}
			<br /><br />- {l s='Do not forget to insert your order number #%d.' sprintf=$id_order mod='pesetacoin_ps_payment'}
		{else}
			<br /><br />- {l s='Do not forget to insert your order reference %s.' sprintf=$reference mod='pesetacoin_ps_payment'}
		{/if}
		<br /><br />{l s='An email has been sent to you with this information.' mod='pesetacoin_ps_payment'}

		<br /><br /><strong>{l s='Your order will be sent as soon as we receive your payment.' mod='pesetacoin_ps_payment'}</strong>

		<br /><br />{l s='For any questions or for further information, please contact our' mod='pesetacoin_ps_payment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='pesetacoin_ps_payment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We have noticed that there is a problem with your order. If you think this is an error, you can contact our' mod='pesetacoin_ps_payment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' mod='pesetacoin_ps_payment'}</a>.
	</p>
{/if}
