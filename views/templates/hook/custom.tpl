

<div class="panel" id="pesetacoin_list_info">
	<div class="panel-heading">
		{l s='Direcciones Pendientes de Asignar' mod='pesetacoin_ps_payment'}		
	</div>
	<div class="table-responsive-row clearfix">
		<table id="table-carrier" class="table tableDnD carrier">
			<thead>
				<tr class="nodrag nodrop">
					<th class="fixed-width-xs center">
						<span class="title_box">{l s='ID' mod='pesetacoin_ps_payment'}</span>
					</th>
					<th class="">
						<span class="title_box">{l s='Dirección' mod='pesetacoin_ps_payment'}</span>
					</th>
					<th class="">
						<span class="title_box">{l s='Fecha' mod='pesetacoin_ps_payment'}</span>
					</th>
				</tr>				
			</thead>
	
			<tbody>
			{foreach key=cid item=direccion from=$direcciones_pendientes}					
				<tr class="">
					<td class="row-selector text-center">{$direccion.id_ptc}</td>
					<td class="row-selector">{$direccion.token_ptc}</td>
					<td class="row-selector">{$direccion.date_add}</td>
				</tr>				
			{/foreach}
			</tbody>
		
		</table>
	</div>

</div>



<div class="panel" id="pesetacoin_list_info">
	<div class="panel-heading">
		{l s='Direcciones Asignadas a un pedido' mod='pesetacoin_ps_payment'}		
	</div>
	<div class="table-responsive-row clearfix">
		<table id="table-carrier" class="table tableDnD carrier">
			<thead>
				<tr class="nodrag nodrop">
					<th class="fixed-width-xs center">
						<span class="title_box">{l s='ID' mod='pesetacoin_ps_payment'}</span>
					</th>
					<th class="">
						<span class="title_box">{l s='Dirección' mod='pesetacoin_ps_payment'}</span>
					</th>
					<th class="">
						<span class="title_box">{l s='Pedido' mod='pesetacoin_ps_payment'}</span>
					</th>
				</tr>				
			</thead>
	
			<tbody>
			{foreach key=cid item=direccion from=$direcciones_pedido}					
				<tr class="">
					<td class="row-selector text-center">{$direccion.id_ptc}</td>
					<td class="row-selector">{$direccion.token_ptc}</td>
					<td class="row-selector pointer" onclick="document.location = 'index.php?controller=AdminOrders&id_order=21&vieworder&token={$token}'">
						{$direccion.id_pedido_ptc}
					</td>
				</tr>				
			{/foreach}
			</tbody>
		
		</table>
	</div>

</div>

