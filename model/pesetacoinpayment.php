<?php

if (!defined('_PS_VERSION_')) exit;

 class PesetaCoinPaymentFunciones {
	
	private $url = 'https://api.coinmarketcap.com/v1/ticker/pesetacoin/?convert=EUR';
	
	public function __construct()
	{
		
		try {
			$json = file_get_contents($this->url);	
			$this->json_data = json_decode($json, true);
		} catch (Exception $e) {
			$this->json_data[0]['price_eur'] = '--';
			$this->json_data[0]['price_usd'] = '--';
			$this->json_data[0]['price_btc'] = '--';
		}


	}
	
	public function getPriceEur() {	
		return $this->json_data[0]['price_eur'];
	}
	
	public function getPriceUsd() {	
		return $this->json_data[0]['price_usd'];
	}
	
	public function getPriceBtc() {	
		return $this->json_data[0]['price_btc'];
	}
	
}
