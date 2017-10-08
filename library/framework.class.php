<?php

require_once 'config.php';

class FrameWork {
	public $main_currency;
	public $popular_currencies;
	public $template_color;
	public $api_url;
	public $base_url;
	
	public function __construct() {
		$this->base_url = BASE_URL;
		$this->api_url = $this->base_url."/".API_URL;
		$this->main_currency = MAIN_CURRENCY;
		$this->popular_currencies = POPULAR_CURRENCIES;
		$this->template_color = TEMPLATE_COLOR;
	}
	
	public function getTemplateSettings() {
		return $this->template_color;
	}

	public function getMainCurrecyRate($currency='') {
		if ($currency)
			$this->main_currency = $currency;
		
		return json_decode($this->curl('rates/'.$this->main_currency), true);
	}

	public function getPopularCurrencyRates() {
		$arr = [];

		foreach ($this->popular_currencies as $currency)
			$arr[] = json_decode($this->curl('rates/'.$currency), true);

		return $arr;
	}

	public function getAllCurrencyRates() {
		$arr = [];
		$rows = json_decode($this->curl('rates'), true);

		foreach ($rows as $k=>$row ) {
			if ($row['name']) {
				if ($k <= 26) {
					$arr[0][] = $row;
				}else if($k<=53) {
					$arr[1][] = $row;
				}else {
					$arr[2][] = $row;
				}
			}
		}
		
		return $arr;
	}
	//THIS IS USING CURL TO GET FROM OUR API - /api/v1/{endpoint}
	private function curl($end_point) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->api_url."/".$end_point
		));

		$resp = curl_exec($curl);
		curl_close($curl);

		return $resp;
	}		
}

?>