
{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Volver al pago' mod='pesetacoin_ps_payment'}">{l s='Pago' mod='pesetacoin_ps_payment'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Pago en Pesetacoin' mod='pesetacoin_ps_payment'}
{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Resumen de pedido' mod='pesetacoin_ps_payment'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Su carrito de la compra está vacio.' mod='pesetacoin_ps_payment'}</p>
{else}

{if isset($numero_direciones) && $numero_direciones > 0}
	

<h3>{l s='Pago en Pesetacoin' mod='pesetacoin_ps_payment'}</h3>

<form action="{$link->getModuleLink('pesetacoin_ps_payment', 'validation', [], true)|escape:'html'}" method="post">
	<p>
		<img src="{$this_path_pesetacoin_ps_payment}pesetacoin_ps_payment.jpg" alt="{l s='Pesetacoin' mod='pesetacoin_ps_payment'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;" />
		
                {l s='Ha elegido pagar en Pesetacoin.' mod='pesetacoin_ps_payment'}

		<br/><br />
		{l s='A continuación se muestra un resumen de su pedido:' mod='pesetacoin_ps_payment'}
	</p>
	
    <p style="margin-top:20px;">
		- {l s='El total de su pedido asciende a:' mod='pesetacoin_ps_payment'}<span id="amount" class="price">{displayPrice price=$total}</span>{if $use_taxes == 1}{l s='(tax incl.)' mod='pesetacoin_ps_payment'}{/if}
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
		<b>{l s='Por favor, confirme su pedido pinchando en \'Confirmar pedido\'.' mod='pesetacoin_ps_payment'}</b>
	</p>

<br/><br />

	<p class="cart_navigation" id="cart_navigation">
		<input type="submit" value="{l s='Confirmar pedido' mod='pesetacoin_ps_payment'}" class="exclusive_large"/>
		<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Otros métodos de pago' mod='pesetacoin_ps_payment'}</a>
	</p>

</form>

{else}
      <p class="warning">
           
           {l s='Existe un problema con su pedido. Puede contactar con nuestro' mod='pesetacoin_ps_payment'}  
           <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='departamento de atención al cliente.' mod='pesetacoin_ps_payment'}</a>.
           <br/><br/>
           {l s='No hay direcciones de pago para pesetacoin.' mod='pesetacoin_ps_payment'}

      </p>

{/if}

{/if}
