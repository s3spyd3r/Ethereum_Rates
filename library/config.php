<?php 

define('BASE_URL','http://www.rodriguesfilipe.net/ethereum');
define('API_URL','api/v1');
define('MY_LOCALE','en-US');
define('MAIN_CURRENCY','USD');
const POPULAR_CURRENCIES = array('EUR','USD','GBP','CHF');

define('BRAND_NAME','Ethereum Rates');

define('TEMPLATE_COLOR','#4C7595');
define('META_TITLE','Ethereum Rates');
define('META_INDIVIDUAL_TITLE','Ethereum Rates For - ');

define('META_DESCRIPTION','A PHP app to display live Ethereum Rates for over 79 currencies.');

$debug = true;

if ($debug) {
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}
?>