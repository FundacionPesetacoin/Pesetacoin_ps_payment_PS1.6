CREATE TABLE IF NOT EXISTS `PREFIX_pesetacoin_ps_payment` (
  `id_ptc` int(11) NOT NULL AUTO_INCREMENT,
  `token_ptc` text NOT NULL,
  `estado_ptc` tinyint(1) NOT NULL,
  `id_pedido_ptc` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_ptc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;