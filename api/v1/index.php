<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once '../../library/config.php';
require_once 'vendor/autoload.php';
require_once 'RateService.php';

$currencies = require 'currencies.php';
$rateService = new RateService(__DIR__ . '/json', $currencies);

$app = new \Slim\App;

$app->group('/{crypto}', function () use ($rateService) {
    $this->get('/rates', function (Request $request, Response $response, array $args) use ($rateService) {
        try {
            $rates = $rateService->getRates($args['crypto']);
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

    $this->get('/rates/{currency}', function (Request $request, Response $response, array $args) use ($rateService) {
        try {
            $rates = $rateService->getRates($args['crypto'], $args['currency']);
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

    $this->get('/calculate/{amount}/{currency}', function (Request $request, Response $response, array $args) use ($rateService) {
        try {
            $amount = (float) $args['amount'];
            if ($amount <= 0) {
                throw new \InvalidArgumentException('Amount must be a positive number');
            }
            $calculation = $rateService->calculate($args['crypto'], $amount, $args['currency']);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode($calculation));
            return $response;
        } catch (\Exception $e) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(['error' => true, 'message' => $e->getMessage()]));
            return $response;
        }
    });
})->add(function ($request, $response, $next) {
    $route = $request->getAttribute('route');
    $crypto = $route->getArgument('crypto');
    if (!in_array(strtoupper($crypto), SUPPORTED_CRYPTOS)) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(400);
        $response->getBody()->write(json_encode(['error' => true, 'message' => 'Unsupported cryptocurrency']));
        return $response;
    }
    return $next($request, $response);
});


$app->run();