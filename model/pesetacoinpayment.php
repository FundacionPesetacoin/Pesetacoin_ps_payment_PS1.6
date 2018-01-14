<?php

if (!defined('_PS_VERSION_')) exit;


/**
 * Clase auxiliar para muestra de informacion de pesetacoin
 * con datos de coinmarketcap
 *
 * @package    moduloejemplo
 * @subpackage
 * @author     marcos.trfn@gmail.com
 * @version    1.0
 *
 *
 *'https://api.coinmarketcap.com/v1/ticker/pesetacoin/?convert=EUR';
 *
 * [{
 * 	"id": "pesetacoin",
 *     "name": "Pesetacoin",
 *     "symbol": "PTC",
 *     "rank": "385",
 *     "price_usd": "0.130291",
 *     "price_btc": "0.00000870",
 *     "24h_volume_usd": "817149.0",
 *     "market_cap_usd": "17217147.0",
 *     "available_supply": "132143794.0",
 *     "total_supply": "132143794.0",
 *     "max_supply": null,
 *     "percent_change_1h": "3.27",
 *     "percent_change_24h": "-11.06",
 *     "percent_change_7d": "37.25",
 *     "last_updated": "1514980742",
 *     "price_eur": "0.1084159228",
 *     "24h_volume_eur": "679954.585794",
 *     "market_cap_eur": "14326491.0"
 *  }]
 *
 */
 
 
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
