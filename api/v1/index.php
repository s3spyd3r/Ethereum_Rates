<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once '../../library/config.php';
require_once 'vendor/autoload.php';
require_once 'RateService.php';

$currencies = require 'currencies.php';
$rateService = new RateService(__DIR__ . '/json', $currencies);

$app = new \Slim\App;

$app->get('/rates', function (Request $request, Response $response) use ($rateService) {
    try {
		$rates = $rateService->getRates();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($rates));
        return $response;
    } catch (\Exception $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(400);
        $response->getBody()->write(json_encode(['error' => true, 'message' => $e->getMessage()]));
        return $response;
    }
});

$app->get('/rates/{currency}', function (Request $request, Response $response, array $args) use ($rateService) {
    try {
        $rates = $rateService->getRates($args['currency']);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($rates));
        return $response;
    } catch (\Exception $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(400);
        $response->getBody()->write(json_encode(['error' => true, 'message' => $e->getMessage()]));
        return $response;
    }
});

$app->get('/calculate/{amount}/{currency}', function (Request $request, Response $response, array $args) use ($rateService) {
    try {
        $amount = (float) $args['amount'];
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be a positive number');
        }
        $calculation = $rateService->calculate($amount, $args['currency']);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($calculation));
        return $response;
    } catch (\Exception $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(400);
        $response->getBody()->write(json_encode(['error' => true, 'message' => $e->getMessage()]));
        return $response;
}});

$app->run();
