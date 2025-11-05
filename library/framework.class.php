<?php

require_once 'config.php';

class FrameWork {
    public string $main_currency;
    public array $popular_currencies;
    public string $template_color;
    public string $api_url;
    public string $base_url;

    public function __construct() {
        $this->base_url = BASE_URL;
        $this->api_url = API_URL; // API_URL from config.php is now an absolute URL
        $this->main_currency = MAIN_CURRENCY;
        $this->popular_currencies = POPULAR_CURRENCIES;
        $this->template_color = TEMPLATE_COLOR;
    }

    public function getTemplateSettings(): string {
        return $this->template_color;
    }

    public function getMainCurrecyRate(string $currency = ''): array {
        if (!empty($currency)) {
            $this->main_currency = $currency;
        }

        if (empty($this->main_currency)) {
            throw new \InvalidArgumentException('No currency specified');
        }

        $response = $this->curl('rates/' . $this->main_currency);
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new \RuntimeException('Invalid currency data received');
        }

        return $data;
    }

    public function getPopularCurrencyRates(): array {
        $allRates = $this->getAllCurrencyRatesFlattened();
        $popularRates = [];

        if (!empty($this->popular_currencies) && is_array($this->popular_currencies)) {
            $popularCurrencies = array_flip($this->popular_currencies);
            foreach ($allRates as $rate) {
                if (isset($popularCurrencies[$rate['code']])) {
                    $popularRates[] = $rate;
                }
            }
        }

        return $popularRates;
    }

    public function getAllCurrencyRates(): array {
        $allRates = $this->getAllCurrencyRatesFlattened();
        return array_chunk($allRates, 27);
    }

    private function getAllCurrencyRatesFlattened(): array {
        try {
            $response = $this->curl('rates');
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                // Handle cases where the API returns something that is not valid JSON
                return [];
            }
            
            // The API returns an array with an 'error' key on failure.
            if (isset($data['error']) && $data['error']) {
                // Or if it returns a json error
                return [];
            }
            
            // The api can return the data in a 'data' key
            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }

            return $data;
        } catch (\Exception $e) {
            // Log the exception message if you have a logger
            return [];
        }
    }

    private function curl(string $end_point): string {
        $curl = curl_init();
        if ($curl === false) {
            throw new \RuntimeException('Failed to initialize cURL');
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->api_url . "/" . $end_point,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $resp = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl) || $httpCode !== 200) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \RuntimeException("cURL Error: " . $error . " (HTTP Code: " . $httpCode . ")");
        }

        curl_close($curl);
        
        if ($resp === false) {
            throw new \RuntimeException('cURL returned false, but no error was reported.');
        }

        return $resp;
    }
}
