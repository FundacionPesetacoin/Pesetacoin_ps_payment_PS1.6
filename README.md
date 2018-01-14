# Pesetacoin_ps_payment_PS1.6
Módulo de pago en pesetacoin para prestashop 1.6

## Pasos previos a la instalación

#### Plantilla de correo electrónico

El módulo de pago usa dos plantillas para enviar el correo electrónico de confirmación. Las plantillas payment_ptc.txt y payment_ptc.html. Ambas plantillas están en ls carpeta mails/es/

Hay que subir estas dos plantillas a la carpeta de la instalación de Prestashop /mails/es/

#### Añadir Estado 

En el panel de Administración de PrestaShop, Pedidos / Estados hay que añadir un nuevo estado.

* En nombre de Pedido "Esperando pago en PesetaCoin"
* Seleccionar el Icono y el color (no es obligatorio).
* Marcar la casilla "Enviar un correo electrónico al cliente cuando el estado de su pedido ha cambiado."

En el campo Plantilla seleccionar "payment_ptc" (esta plantilla debe encontrase en la carpeta mails/es/ del servidor)

PrestaShop asigna un número de Id al estado creado. Este número de Id es necesario para configurar el módulo de pago una vez instalado.

## Instalación

Instalar el módulo del modo normal.

## Configuración del módulo

Una vez instalado, es obligatorio introducir los parámetros de configuración siguientes:

* Id de Estado. El id del Estado creado como "Esperando pago en PesetaCoin"
* Dirección de pago. La dirección para recibir el pago.

## Notas

Actualmente solo acepta una dirección de pago. Falta preparar el módulo para introducir varias direcciones.



