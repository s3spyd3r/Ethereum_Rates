<?php
//SET THE DEFAULT TIMEZONE BASED ON THE SERVER
date_default_timezone_set(@date_default_timezone_get());

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once('../../library/config.php');
require_once('vendor/autoload.php');

$app = new \Slim\App;

//API ENDPOINT TO GET ALL RATES

$app->get('/rates', function (Request $request, Response $response) {
   $rates = getRates();
   $status = (!$rates['error']) ? 200 : 400;
   $response = $response->withHeader('Content-Type', 'application/json');
   $response = $response->withStatus($status);
   $response->getBody()->write(json_encode($rates));
   return $response;
});

//API ENDPOINT TO GET RATES FOR A CERTAIN CURRENCY

$app->get('/rates/{currency}', function (Request $request, Response $response) {
   $currency = $request->getAttribute('currency');
   if($currency || !is_string($currency)) {
	   $rates = getRates($currency);
	   $status = 200;
   } else {
	   $rates = array('error'=>true,'message'=>'Could not fetch results');
	   $status = 400;
   }
   $response = $response->withHeader('Content-Type', 'application/json');
   $response = $response->withStatus($status);
   $response->getBody()->write(json_encode($rates));
   return $response;
});

//API ENDPOINT FOR CALCULATING A CERTAIN CURRENCY
$app->get('/calculate/{amount}/{currency}', function (Request $request, Response $response) {
  
	$amount = $request->getAttribute('amount');
	$currency = $request->getAttribute('currency');
  
	if(!$amount || !is_string($currency) || !is_numeric($amount)) {
		$arr = array('error'=>true,'message'=>'Could not fetch results');
		$status = 400;
	} else {
		$rate = getRates($currency);
		if (isset($rate['error']) && $rate['error']) {
			$arr = array('error'=>true,'message'=>'Could not fetch rate for currency: ' . $currency);
			$status = 400;
		} else if (!isset($rate['rate'])) {
			$arr = array('error'=>true,'message'=>'Rate not available for currency: ' . $currency);
			$status = 400;
		} else {
			$calc = round($amount * $rate['rate']);
			$formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
			$calc = $formatter->formatCurrency($calc, $currency);
			$arr = array('amount'=>$amount,'calc'=>$calc,'currency'=>$currency);
			$status = 200;
		}
	}

	$response = $response->withHeader('Content-Type', 'application/json');
	$response = $response->withStatus($status);
	$response->getBody()->write(json_encode($arr));
	return $response;
});

//1. CHECK IF WE HAVE A FILE WITHIN A MINUTE OLD
//2. QUERIES BITPAY IF WE DO NOT
//3. SAVES THE FILE FOR LOCAL JSON CACHE
//4. RETURNS AN ARRAY FOR JSON OUTPUT
function getRates($currency='') {
    $date = new DateTime();
    $time = time();
    $file = 'json/'.$time.".json";
    $arr  = array();
    $api_url = "https://api.coinbase.com/v2/exchange-rates?currency=ETH";
    
    try {
        // Make the json directory path absolute
        $jsonDir = __DIR__ . '/json';
        if (!is_dir($jsonDir)) {
            if (!mkdir($jsonDir, 0777, true)) {
                error_log("Failed to create directory: " . $jsonDir);
                throw new Exception('Failed to create json directory');
            }
        }
        
        // Make file path absolute
        $file = $jsonDir . '/' . $time . ".json";
	
	$api_currencies = array
	(
		"AED" => array
			(
				"code" => "AED",
				"rate" => 0,
				"name" => "UAE Dirham",
			),
	
		"AFN" => array
			(
				"code" => "AFN",
				"rate" => 0,
				"name" => "Afghan Afghani",
			),
	
		"ALL" => array
			(
				"code" => "ALL",
				"rate" => 0,
				"name" => "Albanian Lek",
			),
	
		"AOA" => array
			(
				"code" => "AOA",
				"rate" => 0,
				"name" => "Angolan Kwanza",
			),
	
		"ARS" => array
			(
				"code" => "ARS",
				"rate" => 0,
				"name" => "Argentine Peso",
			),
	
		"AUD" => array
			(
				"code" => "AUD",
				"rate" => 0,
				"name" => "Australian Dollar",
			),
	
		"AZN" => array
			(
				"code" => "AZN",
				"rate" => 0,
				"name" => "Azerbaijani Manat",
			),
	
		"BAM" => array
			(
				"code" => "BAM",
				"rate" => 0,
				"name" => "Bosnia-Herzegovina Convertible Mark",
			),
	
		"BDT" => array
			(
				"code" => "BDT",
				"rate" => 0,
				"name" => "Bangladeshi Taka",
			),
	
		"BGN" => array
			(
				"code" => "BGN",
				"rate" => 0,
				"name" => "Bulgarian Lev",
			),
	
		"BHD" => array
			(
				"code" => "BHD",
				"rate" => 0,
				"name" => "Bahraini Dinar",
			),
	
		"BOB" => array
			(
				"code" => "BOB",
				"rate" => 0,
				"name" => "Bolivian Boliviano",
			),
	
		"BRL" => array
			(
				"code" => "BRL",
				"rate" => 0,
				"name" => "Brazilian Real",
			),
	
		"BSD" => array
			(
				"code" => "BSD",
				"rate" => 0,
				"name" => "Bahamian Dollar",
			),
	
		"BWP" => array
			(
				"code" => "BWP",
				"rate" => 0,
				"name" => "Botswanan Pula",
			),
	
		"BYR" => array
			(
				"code" => "BYR",
				"rate" => 0,
				"name" => "Belarusian Ruble",
			),
	
		"CAD" => array
			(
				"code" => "CAD",
				"rate" => 0,
				"name" => "Canadian Dollar",
			),
	
		"CHF" => array
			(
				"code" => "CHF",
				"rate" => 0,
				"name" => "Swiss Franc",
			),
	
		"CLP" => array
			(
				"code" => "CLP",
				"rate" => 0,
				"name" => "Chilean Peso",
			),
	
		"CNY" => array
			(
				"code" => "CNY",
				"rate" => 0,
				"name" => "Chinese Yuan",
			),
	
		"COP" => array
			(
				"code" => "COP",
				"rate" => 0,
				"name" => "Colombian Peso",
			),
	
		"CRC" => array
			(
				"code" => "CRC",
				"rate" => 0,
				"name" => "Costa Rican Colón",
			),
	
		"CZK" => array
			(
				"code" => "CZK",
				"rate" => 0,
				"name" => "Czech Koruna",
			),
	
		"DKK" => array
			(
				"code" => "DKK",
				"rate" => 0,
				"name" => "Danish Krone",
			),
	
		"DOP" => array
			(
				"code" => "DOP",
				"rate" => 0,
				"name" => "Dominican Peso",
			),
	
		"DZD" => array
			(
				"code" => "DZD",
				"rate" => 0,
				"name" => "Algerian Dinar",
			),
	
		"EGP" => array
			(
				"code" => "EGP",
				"rate" => 0,
				"name" => "Egyptian Pound",
			),
	
		"EUR" => array
			(
				"code" => "EUR",
				"rate" => 0,
				"name" => "Eurozone Euro",
			),
	
		"GBP" => array
			(
				"code" => "GBP",
				"rate" => 0,
				"name" => "Pound Sterling",
			),
	
		"GHS" => array
			(
				"code" => "GHS",
				"rate" => 0,
				"name" => "Ghanaian Cedi",
			),
	
		"GIP" => array
			(
				"code" => "GIP",
				"rate" => 0,
				"name" => "Gibraltar Pound",
			),
	
		"GTQ" => array
			(
				"code" => "GTQ",
				"rate" => 0,
				"name" => "Guatemalan Quetzal",
			),
	
		"HKD" => array
			(
				"code" => "HKD",
				"rate" => 0,
				"name" => "Hong Kong Dollar",
			),
	
		"HNL" => array
			(
				"code" => "HNL",
				"rate" => 0,
				"name" => "Honduran Lempira",
			),
	
		"HRK" => array
			(
				"code" => "HRK",
				"rate" => 0,
				"name" => "Croatian Kuna",
			),
	
		"HUF" => array
			(
				"code" => "HUF",
				"rate" => 0,
				"name" => "Hungarian Forint",
			),
	
		"IDR" => array
			(
				"code" => "IDR",
				"rate" => 0,
				"name" => "Indonesian Rupiah",
			),
	
		"ILS" => array
			(
				"code" => "ILS",
				"rate" => 0,
				"name" => "Israeli Shekel",
			),
	
		"INR" => array
			(
				"code" => "INR",
				"rate" => 0,
				"name" => "Indian Rupee",
			),
	
		"IQD" => array
			(
				"code" => "IQD",
				"rate" => 0,
				"name" => "Iraqi Dinar",
			),
	
		"ISK" => array
			(
				"code" => "ISK",
				"rate" => 0,
				"name" => "Icelandic Króna",
			),
	
		"JMD" => array
			(
				"code" => "JMD",
				"rate" => 0,
				"name" => "Jamaican Dollar",
			),
	
		"JOD" => array
			(
				"code" => "JOD",
				"rate" => 0,
				"name" => "Jordanian Dinar",
			),
	
		"JPY" => array
			(
				"code" => "JPY",
				"rate" => 0,
				"name" => "Japanese Yen",
			),
	
		"KES" => array
			(
				"code" => "KES",
				"rate" => 0,
				"name" => "Kenyan Shilling",
			),
	
		"KGS" => array
			(
				"code" => "KGS",
				"rate" => 0,
				"name" => "Kyrgystani Som",
			),
	
		"KHR" => array
			(
				"code" => "KHR",
				"rate" => 0,
				"name" => "Cambodian Riel",
			),
	
		"KRW" => array
			(
				"code" => "KRW",
				"rate" => 0,
				"name" => "South Korean Won",
			),
	
		"KWD" => array
			(
				"code" => "KWD",
				"rate" => 0,
				"name" => "Kuwaiti Dinar",
			),
	
		"KZT" => array
			(
				"code" => "KZT",
				"rate" => 0,
				"name" => "Kazakhstani Tenge",
			),
	
		"LBP" => array
			(
				"code" => "LBP",
				"rate" => 0,
				"name" => "Lebanese Pound",
			),
	
		"LKR" => array
			(
				"code" => "LKR",
				"rate" => 0,
				"name" => "Sri Lankan Rupee",
			),
	
		"LSL" => array
			(
				"code" => "LSL",
				"rate" => 0,
				"name" => "Lesotho Loti",
			),
	
		"MAD" => array
			(
				"code" => "MAD",
				"rate" => 0,
				"name" => "Moroccan Dirham",
			),
	
		"MUR" => array
			(
				"code" => "MUR",
				"rate" => 0,
				"name" => "Mauritian Rupee",
			),
	
		"MXN" => array
			(
				"code" => "MXN",
				"rate" => 0,
				"name" => "Mexican Peso",
			),
	
		"MYR" => array
			(
				"code" => "MYR",
				"rate" => 0,
				"name" => "Malaysian Ringgit",
			),
	
		"NAD" => array
			(
				"code" => "NAD",
				"rate" => 0,
				"name" => "Namibian Dollar",
			),
	
		"NGN" => array
			(
				"code" => "NGN",
				"rate" => 0,
				"name" => "Nigerian Naira",
			),
	
		"NOK" => array
			(
				"code" => "NOK",
				"rate" => 0,
				"name" => "Norwegian Krone",
			),
	
		"NZD" => array
			(
				"code" => "NZD",
				"rate" => 0,
				"name" => "New Zealand Dollar",
			),
	
		"OMR" => array
			(
				"code" => "OMR",
				"rate" => 0,
				"name" => "Omani Rial",
			),
	
		"PAB" => array
			(
				"code" => "PAB",
				"rate" => 0,
				"name" => "Panamanian Balboa",
			),
	
		"PEN" => array
			(
				"code" => "PEN",
				"rate" => 0,
				"name" => "Peruvian Nuevo Sol",
			),
	
		"PHP" => array
			(
				"code" => "PHP",
				"rate" => 0,
				"name" => "Philippine Peso",
			),
	
		"PKR" => array
			(
				"code" => "PKR",
				"rate" => 0,
				"name" => "Pakistani Rupee",
			),
	
		"PLN" => array
			(
				"code" => "PLN",
				"rate" => 0,
				"name" => "Polish Zloty",
			),
	
		"PYG" => array
			(
				"code" => "PYG",
				"rate" => 0,
				"name" => "Paraguayan Guarani",
			),
	
		"QAR" => array
			(
				"code" => "QAR",
				"rate" => 0,
				"name" => "Qatari Rial",
			),
	
		"RON" => array
			(
				"code" => "RON",
				"rate" => 0,
				"name" => "Romanian Leu",
			),
	
		"RSD" => array
			(
				"code" => "RSD",
				"rate" => 0,
				"name" => "Serbian Dinar",
			),
	
		"RUB" => array
			(
				"code" => "RUB",
				"rate" => 0,
				"name" => "Russian Ruble",
			),
	
		"RWF" => array
			(
				"code" => "RWF",
				"rate" => 0,
				"name" => "Rwandan Franc",
			),
	
		"SAR" => array
			(
				"code" => "SAR",
				"rate" => 0,
				"name" => "Saudi Riyal",
			),
	
		"SBD" => array
			(
				"code" => "SBD",
				"rate" => 0,
				"name" => "Solomon Islands Dollar",
			),
	
		"SEK" => array
			(
				"code" => "SEK",
				"rate" => 0,
				"name" => "Swedish Krona",
			),
	
		"SGD" => array
			(
				"code" => "SGD",
				"rate" => 0,
				"name" => "Singapore Dollar",
			),
	
		"SVC" => array
			(
				"code" => "SVC",
				"rate" => 0,
				"name" => "Salvadoran Colón",
			),
	
		"THB" => array
			(
				"code" => "THB",
				"rate" => 0,
				"name" => "Thai Baht",
			),
	
		"USD" => array
			(
				"code" => "USD",
				"rate" => 0,
				"name" => "US Dollar",
			),
	);
	
	try {
        $files = @scandir($jsonDir, SCANDIR_SORT_DESCENDING);
        if ($files === false) {
            error_log("Failed to scan directory: " . $jsonDir);
            throw new Exception('Failed to read cache directory');
        }
        
        $last = 0;
        foreach ($files as $f) {
            if (preg_match('/^(\d+)\.json$/', $f, $matches)) {
                $last = (int)$matches[1];
                break;
            }
        }

        if($last && $last+60 >= $time) {
            $cacheFile = $jsonDir . '/' . $last . ".json";
            $json = @file_get_contents($cacheFile);
            if ($json === false) {
                error_log("Failed to read cache file: " . $cacheFile);
                throw new Exception('Failed to read cached JSON file');
            }
        } else {
            // Use CURL instead of file_get_contents
            if (!function_exists('curl_init')) {
                error_log("CURL is not installed");
                throw new Exception('CURL is not installed');
            }

            $ch = curl_init();
            if ($ch === false) {
                error_log("Failed to initialize CURL");
                throw new Exception('Failed to initialize CURL');
            }

            curl_setopt_array($ch, [
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_USERAGENT => 'Mozilla/5.0'
            ]);

            $api_response = curl_exec($ch);
            $curl_error = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            error_log("API Response Code: " . $http_code);
            if ($curl_error) {
                error_log("CURL Error: " . $curl_error);
            }

            if ($api_response === false) {
                throw new Exception('Failed to fetch data from Coinbase API: ' . $curl_error);
            }

            if ($http_code !== 200) {
                throw new Exception('API returned error code: ' . $http_code);
            }
            
            $a = json_decode($api_response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode error: ' . json_last_error_msg());
            }
            
            if (!isset($a['data'])) {
                error_log('API Response: ' . print_r($a, true));
                throw new Exception('Invalid API response format - data field missing');
            }
            
            $arr = $a['data'];
            $json = json_encode($arr);
            if ($json === false) {
                throw new Exception('Failed to encode API response');
            }
            
            if (@file_put_contents($file, $json) === false) {
                throw new Exception('Failed to write cache file');
            }
        }
    } catch (Exception $e) {
        error_log('Ethereum Rates API Error: ' . $e->getMessage());
        return array('error' => true, 'message' => 'Could not fetch exchange rates. Please try again later.');
    }
	
        $jsonArr = json_decode($json, true);
        if ($jsonArr === null) {
            throw new Exception('Failed to decode JSON data');
        }

        $arr = array();
        // Check if we can use NumberFormatter
        if (class_exists('NumberFormatter')) {
            $formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
        }
        
        if (!isset($jsonArr['rates'])) {
            error_log('Invalid JSON structure: ' . print_r($jsonArr, true));
            throw new Exception('Invalid rate data format');
        }
        
		foreach($jsonArr['rates'] as $code=>$rate) {
			if(array_key_exists($code, $api_currencies)) {
				if(!$currency) {
					$a = $api_currencies[$code];
					$a['rate'] = $rate;
					$a['rate_formatted'] = $formatter->formatCurrency($rate, $code);
					$arr[] = $a;
				}else{
					if($code == strtoupper($currency)) {
						$a = $api_currencies[$code];
						$a['rate'] = $rate;
						$a['rate_formatted'] = $formatter->formatCurrency($rate, $code);
						$arr = $a;
						break;
					}
				}
			}
		}
        
        if(!sizeof($arr)) {
            throw new Exception('No matching currency rates found');
        }
        
        $arr['error'] = false;
        return $arr;
        
    } catch (Exception $e) {
        error_log('Ethereum Rates API Error: ' . $e->getMessage());
        return array('error' => true, 'message' => 'Could not fetch exchange rates. Please try again later.');
    }
}

$app->run();