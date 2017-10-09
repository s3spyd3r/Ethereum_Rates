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
   
	if (!$rates['error'])
		$status = 200;
	else
		$status = 400;
   
	return $response->withJson($rates, $status);
});

//API ENDPOINT TO GET RATES FOR A CERTAIN CURRENCY
$app->get('/rates/{currency}', function (Request $request, Response $response) {
  	
	$currency = $request->getAttribute('currency');
	
	if($currency || !is_string($currency)) {
		$rates = getRates($currency);
   		$status = 200;
	}else {
		$rates = array('error'=>true,'message'=>'Could not fetch results');
		$status = 400;
	}
   
	return $response->withJson($rates, $status);
});

//API ENDPOINT FOR CALCULATING A CERTAIN CURRENCY
$app->get('/calculate/{amount}/{currency}', function (Request $request, Response $response) {
  
	$amount = $request->getAttribute('amount');
	$currency = $request->getAttribute('currency');
  
	if(!$amount || !is_string($currency) || !is_numeric($amount)) {
		$arr = array('error'=>true,'message'=>'Could not fetch results');
		$status = 400;
	}else {
		$rate = getRates($currency); 
		$calc = round($amount * $rate['rate']);
	   
		$formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
		$calc = $formatter->formatCurrency($calc, $currency);
	   
		$arr = array('amount'=>$amount,'calc'=>$calc,'currency'=>$currency);
		$status = 200;
	}

	return $response->withJson($arr, $status);
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
	
	$files = scandir('json', SCANDIR_SORT_DESCENDING);
	$last  = (int)str_replace(".json","",$files[0]);

	if($last && $last+60 >= $time) {
		$json = file_get_contents('json/'.$last.".json");
	}else {
		$a = json_decode(file_get_contents($api_url), true);
		$arr = $a['data'];
		$json = json_encode($arr);
		file_put_contents($file, $json);
	}
	
	$jsonArr = json_decode($json, true);

	$arr = array();
	$formatter = new NumberFormatter(MY_LOCALE, NumberFormatter::CURRENCY);
	
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
	
	if(!sizeof($arr))
		$arr = array('error'=>true,'message'=>'Could not fetch results.');
	else
		$arr['error'] = false;
	
	return $arr;
}

$app->run();