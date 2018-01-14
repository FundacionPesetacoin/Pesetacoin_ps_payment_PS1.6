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

{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='pesetacoin_ps_payment'}">{l s='Checkout' mod='pesetacoin_ps_payment'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Pesetacoin payment' mod='pesetacoin_ps_payment'}
{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='pesetacoin_ps_payment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='pesetacoin_ps_payment'}</p>
{else}

<h3>{l s='Pesetacoin payment' mod='pesetacoin_ps_payment'}</h3>

<form action="{$link->getModuleLink('pesetacoin_ps_payment', 'validation', [], true)|escape:'html'}" method="post">
	<p>
		<img src="{$this_path_pesetacoin_ps_payment}pesetacoin_ps_payment.jpg" alt="{l s='Check' mod='pesetacoin_ps_payment'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
		
                {l s='You have chosen to pay by pesetacoin.' mod='pesetacoin_ps_payment'}

		<br/><br />

		{l s='Here is a short summary of your order:' mod='pesetacoin_ps_payment'}
	</p>
	
        <p style="margin-top:20px;">

		- {l s='The total amount of your order comes to:' mod='pesetacoin_ps_payment'}
		<span id="amount" class="price">{displayPrice price=$total}</span>
		{if $use_taxes == 1}
			{l s='(tax incl.)' mod='pesetacoin_ps_payment'}
		{/if}
	</p>

        <p style="margin-top:20px;">

		- {l s='El valor en PesetaCoin es:' mod='pesetacoin_ps_payment'}
		<span id="amount" class="price">{$importePtc}</span>
	</p>

        <p style="margin-top:20px;">

                - {l s='Debe ingresar el valor en la siguiente direccion de PesetaCoin:' mod='pesetacoin_ps_payment'}
		<span id="amount" class="price">{$direccion}</span>
	</p>
	
	<p>
		<b>{l s='Please confirm your order by clicking \'I confirm my order\'.' mod='pesetacoin_ps_payment'}</b>
	</p>

<br/><br />

	<p class="cart_navigation" id="cart_navigation">
		<input type="submit" value="{l s='I confirm my order' mod='pesetacoin_ps_payment'}" class="exclusive_large"/>
		<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Other payment methods' mod='pesetacoin_ps_payment'}</a>
	</p>

</form>
{/if}
