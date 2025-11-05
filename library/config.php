<?php 

// It's recommended to use const for defining constants in modern PHP.
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', dirname(__DIR__))));
define('API_URL', BASE_URL . '/api/v1');
const MY_LOCALE = 'en-US';
const MAIN_CURRENCY = 'USD';
const POPULAR_CURRENCIES = ['EUR', 'USD', 'GBP', 'CHF'];

const BRAND_NAME = 'Ethereum Rates';

const TEMPLATE_COLOR = '#4C7595';
const META_TITLE = 'Ethereum Rates';
const META_INDIVIDUAL_TITLE = 'Ethereum Rates For - ';

const META_DESCRIPTION = 'A PHP app to display live Ethereum Rates for over 79 currencies.';

$debug = true;

if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

