<?php

class RateService {
    private string $cacheDir;
    private array $currencies;
    private string $apiUrl = 'https://api.coinbase.com/v2/exchange-rates?currency=ETH';

    public function __construct(string $cacheDir, array $currencies) {
        $this->cacheDir = $cacheDir;
        $this->currencies = $currencies;
    }

    public function getRates(string $currency = ''): array {
        $data = $this->getRatesFromCacheOrApi();

        if (!empty($currency)) {
            $currency = strtoupper($currency);
            if (isset($data[$currency])) {
                return $this->formatRate($currency, $data[$currency]);
            } else {
                throw new \InvalidArgumentException('Currency not found');
            }
        }

        $formattedRates = [];
        foreach ($data as $code => $rate) {
            if (isset($this->currencies[$code])) {
                $formattedRates[] = $this->formatRate($code, $rate);
            }
        }

        return $formattedRates;
    }
    
    public function calculate(float $amount, string $currency): array {
        $rates = $this->getRates($currency);
        $calc = round($amount * $rates['rate']);
        $formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
        $calcFormatted = $formatter->formatCurrency($calc, $currency);

        return [
            'amount' => $amount,
            'calc' => $calcFormatted,
            'currency' => $currency,
        ];
    }

    private function getRatesFromCacheOrApi(): array {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        $cacheFile = $this->cacheDir . '/rates.json';
        $cacheTime = $this->cacheDir . '/rates.time';

        if (file_exists($cacheFile) && file_exists($cacheTime) && (time() - filemtime($cacheTime) < 60)) {
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            if (json_last_error() === JSON_ERROR_NONE && isset($cachedData['data']['rates'])) {
                return $cachedData['data']['rates'];
            }
        }

        $response = $this->fetchFromApi();
        file_put_contents($cacheFile, $response);
        touch($cacheTime);

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['data']['rates'])) {
            throw new \RuntimeException('Invalid API response');
        }

        return $data['data']['rates'];
    }

    private function fetchFromApi(): string {
        $ch = curl_init();
        if ($ch === false) {
            throw new \RuntimeException('Failed to initialize cURL');
        }

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_USERAGENT => 'Mozilla/5.0'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $httpCode !== 200) {
            throw new \RuntimeException('Failed to fetch data from API: ' . $error . ' (HTTP Code: ' . $httpCode . ')');
        }

        return $response;
    }

    private function formatRate(string $code, float $rate): array {
        $formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
        return [
            'code' => $code,
            'name' => $this->currencies[$code],
            'rate' => $rate,
            'rate_formatted' => $formatter->formatCurrency($rate, $code),
        ];
    }
}
