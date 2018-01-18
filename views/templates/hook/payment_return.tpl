
{if $status == 'ok'}
	<p>{l s='Su orden en %s está completa.' sprintf=$shop_name mod='pesetacoin_ps_payment'}
		<br /><br />{l s='Se le ha enviado un correo electrónico con la información de su pedido.' mod='pesetacoin_ps_payment'}

		<br /><br /><strong>{l s='Su pedido será enviado tan pronto como recibamos el pago.' mod='pesetacoin_ps_payment'}</strong>

		<br /><br />{l s='Para cualquier dudo o cuestión, por favor, visite nuestro ' mod='pesetacoin_ps_payment'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='servicio de atención al cliente.' mod='pesetacoin_ps_payment'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='Hay un problema con su pedido. Puede contactar con nuestro ' mod='pesetacoin_ps_payment'} 
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='servicio de atención al cliente.' mod='pesetacoin_ps_payment'}</a>.
	</p>
{/if}
