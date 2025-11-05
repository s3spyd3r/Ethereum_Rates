<?php

require_once 'config.php';

class FrameWork {
    public string $main_currency;
    public array $popular_currencies;
    public string $template_color;
    public string $api_url;
    public string $base_url;
    public string $crypto;

    public function __construct(string $crypto = DEFAULT_CRYPTO) {
        $this->base_url = BASE_URL;
        $this->api_url = API_URL;
        $this->main_currency = MAIN_CURRENCY;
        $this->popular_currencies = POPULAR_CURRENCIES;
        $this->template_color = TEMPLATE_COLOR;
        $this->crypto = $crypto;
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

        $response = $this->curl($this->crypto . '/rates/' . $this->main_currency);
        
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
            $response = $this->curl($this->crypto . '/rates');
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                return [];
            }
            
            if (isset($data['error']) && $data['error']) {
                return [];
            }
            
            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }

            return $data;
        } catch (\Exception $e) {
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