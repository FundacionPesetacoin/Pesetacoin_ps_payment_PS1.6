ALTER TABLE `PREFIX_pesetacoin_ps_payment`
ADD `firstname` VARCHAR( 255 ) NOT NULL AFTER `referencia_pesetacoin_ps_payment` ,
ADD `lastname` VARCHAR( 255 ) NOT NULL AFTER `firstname` ,
ADD `email` VARCHAR( 255 ) NOT NULL AFTER `lastname`