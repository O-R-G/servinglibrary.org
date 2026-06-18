<?php
require_once(__DIR__ . '/../static/php/vendor/autoload.php');
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Security helper functions
function enforceHTTPS() {
	$isLocalhost = isset($_SERVER['HTTP_HOST']) &&
		(strpos($_SERVER['HTTP_HOST'], 'localhost') === 0 ||
		 strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === 0 ||
		 strpos($_SERVER['HTTP_HOST'], 'servinglibrary.local') !== false);

	if (!$isLocalhost && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
		http_response_code(403);
		echo json_encode(['error' => 'HTTPS required']);
		exit;
	}
}

function setCORSHeaders() {
	$allowedOrigin = $_ENV['ALLOWED_ORIGIN'] ?? 'https://servinglibrary.local';
	header('Access-Control-Allow-Origin: ' . $allowedOrigin);
	header('Access-Control-Allow-Methods: POST, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type');

	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		http_response_code(200);
		exit;
	}
}

function validateContentType() {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
		if (strpos($contentType, 'application/json') === false) {
			http_response_code(400);
			echo json_encode(['error' => 'Content-Type must be application/json']);
			exit;
		}
	}
}

function checkRateLimit() {
	$ip = $_SERVER['REMOTE_ADDR'];
	$key = 'api_limit_' . $ip;
	$cacheFile = sys_get_temp_dir() . '/' . md5($key) . '.tmp';

	$data = @file_get_contents($cacheFile);
	$count = $data ? json_decode($data, true) : ['count' => 0, 'reset' => time() + 60];

	if (time() > $count['reset']) {
		$count = ['count' => 0, 'reset' => time() + 60];
	}

	if ($count['count'] >= 10) {
		http_response_code(429);
		echo json_encode(['error' => 'Rate limit exceeded. Max 10 requests per minute.']);
		exit;
	}

	$count['count']++;
	@file_put_contents($cacheFile, json_encode($count));
}

function logAPICall($action, $details = []) {
	$logEntry = [
		'timestamp' => date('Y-m-d H:i:s'),
		'action' => $action,
		'ip' => $_SERVER['REMOTE_ADDR'],
		'details' => $details
	];
	error_log(json_encode($logEntry));
}

function getPayPalAccessToken($currency = 'USD') {
	$currencyUppercase = strtoupper($currency);
	$accountKey = ($currencyUppercase === 'USD') ? 'US' : 'EU';
	return $_ENV['PAYPAL_ACCESS_TOKEN_LIVE_' . $accountKey] ?? false;
}

$shippingFeeByItem_arr = [
	'USD' => [
		'SHIP_US' => [
			'issue' => 7.00,
			'annual' => 12.00,
			'archive' => 35.00,
			'edition' => 45.00,
			'subscription-2' => 14.00,
			'subscription-12' => 84.00
		],
		'SHIP_WORLD' => [
			'issue' => 15.00,
			'annual' => 30.00,
			'archive' => 60.00,
			'edition' => 70.00
		]
	],
	'EUR' => [
		'SHIP_EU' => [
			'issue' => 6.00,
			'annual' => 12.00,
			'archive' => 35.00,
			'edition' => 45.00,
			'subscription-2' => 12.00,
			'subscription-12' => 72.00
		],
		'SHIP_WORLD' => [
			'issue' => 12.00,
			'annual' => 25.00,
			'archive' => 55.00,
			'edition' => 65.00
		]
	],
	'GBP' => [
		'SHIP_UK' => [
			'issue' => 5.00,
			'annual' => 12.00,
			'archive' => 30.00,
			'edition' => 40.00,
			'subscription-2' => 10.00,
			'subscription-12' => 60.00
		],
		'SHIP_WORLD' => [
			'issue' => 10.00,
			'annual' => 20.00,
			'archive' => 45.00,
			'edition' => 55.00
		]
	]
];

function getFeeByAmount($basicFee, $amount) {
	if (is_string($basicFee)) $basicFee = floatval($basicFee);
	if (is_string($amount)) $amount = intval($amount);

	$output = 0;
	$r = 1;
	for ($i = 0; $i < $amount; $i++) {
		if ($i === 1) $r = 2;
		elseif ($i === 2) $r = 3;
		$output += $basicFee / $r;
	}
	return $output;
}

function getTotalShippingFee($items, $shippingOptionId, $currency) {
	global $shippingFeeByItem_arr;

	$output = 0;
	$itemsByType = [];

	foreach ($items as $item) {
		$itemQuantity = intval($item['quantity']);
		$itemType = $item['type'] ?? 'issue';

		if (!isset($itemsByType[$itemType])) {
			$itemsByType[$itemType] = [
				'quantity' => 0,
				'basic_fee' => 0
			];
		}
		$itemsByType[$itemType]['quantity'] += $itemQuantity;
	}

	$currencyUppercase = strtoupper($currency);

	foreach ($itemsByType as $type => $data) {
		if (isset($shippingFeeByItem_arr[$currencyUppercase][$shippingOptionId][$type])) {
			$basicFee = $shippingFeeByItem_arr[$currencyUppercase][$shippingOptionId][$type];
			$output += getFeeByAmount($basicFee, $data['quantity']);
		}
	}

	return round($output, 2);
}
