<?php

require_once 'library/framework.class.php';

$frame_work = new FrameWork();

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
    // You might want to set default values for the variables below
    // to avoid errors in the template if the API call fails.
    $main_currency = [];
    $popular_currencies = [];
    $all_currencies = [[],[],[]];
    $template_color = '#4C7595'; // default color
    $base_url = BASE_URL;
}

require 'templates/main.php';
