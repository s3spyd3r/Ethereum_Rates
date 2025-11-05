<?php

require_once 'library/framework.class.php';

$crypto = $_GET['crypto'] ?? DEFAULT_CRYPTO;
if (!in_array(strtoupper($crypto), SUPPORTED_CRYPTOS)) {
    $crypto = DEFAULT_CRYPTO;
}

$frame_work = new FrameWork($crypto);

$currency = $_GET['currency'] ?? '';
$error_404 = isset($_GET['404']);

try {
    $main_currency = $frame_work->getMainCurrecyRate($currency);
    $popular_currencies = $frame_work->getPopularCurrencyRates();
    $all_currencies = $frame_work->getAllCurrencyRates();
    $template_color = $frame_work->getTemplateSettings();
    $base_url = $frame_work->base_url;
} catch (\Exception $e) {
    $error_message = "Error: " . $e->getMessage();
    $main_currency = [];
    $popular_currencies = [];
    $all_currencies = [[],[],[]];
    $template_color = '#4C7595'; // default color
    $base_url = BASE_URL;
}

require 'templates/main.php';