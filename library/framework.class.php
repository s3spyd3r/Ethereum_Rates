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
		if (!empty($currency)) {
			$this->main_currency = $currency;
		}
		
		if (empty($this->main_currency)) {
			return ['error' => true, 'message' => 'No currency specified'];
		}

		$response = $this->curl('rates/'.$this->main_currency);
		if ($response === false) {
			return ['error' => true, 'message' => 'Failed to fetch currency data'];
		}

		$data = json_decode($response, true);
		if (!is_array($data)) {
			return ['error' => true, 'message' => 'Invalid currency data received'];
		}

		return $data;
	}

	public function getPopularCurrencyRates() {
		$arr = [];
		
		if (!empty($this->popular_currencies) && is_array($this->popular_currencies)) {
			foreach ($this->popular_currencies as $currency) {
				$response = $this->curl('rates/'.$currency);
				if ($response !== false) {
					$data = json_decode($response, true);
					if ($data !== null) {
						$arr[] = $data;
					}
				}
			}
		}

		return $arr;
	}

	public function getAllCurrencyRates() {
		$arr = [[], [], []];  // Initialize all three arrays
		
		$response = $this->curl('rates');
		if ($response === false) {
			return $arr;
		}
		
		$rows = json_decode($response, true);
		if (!is_array($rows)) {
			return $arr;
		}

		foreach ($rows as $k => $row) {
			if (isset($row['name']) && !empty($row['name'])) {
				if ($k <= 26) {
					$arr[0][] = $row;
				} else if ($k <= 53) {
					$arr[1][] = $row;
				} else {
					$arr[2][] = $row;
				}
			}
		}
		
		return $arr;
	}
	//THIS IS USING CURL TO GET FROM OUR API - /api/v1/{endpoint}
	private function curl($end_point) {
		$curl = curl_init();
		if ($curl === false) {
			return false;
		}

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->api_url."/".$end_point,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_SSL_VERIFYPEER => false
		));

		$resp = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if (curl_errno($curl) || $httpCode !== 200) {
			curl_close($curl);
			return false;
		}

		curl_close($curl);
		return $resp;
	}		
}

?>