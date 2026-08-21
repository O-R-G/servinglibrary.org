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
	$allowedOrigin = $_ENV['PAYPAL_ALLOWED_ORIGIN'] ?? 'http://servinglibrary.local';
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

function getPayPalClientId($currency = 'USD') {
	$currencyUppercase = strtoupper($currency);
	$accountKey = ($currencyUppercase === 'USD') ? 'US' : 'EU';
	return $_ENV['PAYPAL_CLIENT_ID_LIVE_' . $accountKey] ?? false;
}

function getPayPalClientSecret($currency = 'USD') {
	$currencyUppercase = strtoupper($currency);
	$accountKey = ($currencyUppercase === 'USD') ? 'US' : 'EU';
	return $_ENV['PAYPAL_CLIENT_SECRET_LIVE_' . $accountKey] ?? false;
}

function getTokenCacheDir() {
	$cacheDir = __DIR__ . '/../.cache/paypal-tokens';
	if (!is_dir($cacheDir)) {
		@mkdir($cacheDir, 0700, true);
		@chmod($cacheDir, 0700);
	}
	return $cacheDir;
}

function getTokenCachePath($currency = 'USD') {
	$currencyUppercase = strtoupper($currency);
	$accountKey = ($currencyUppercase === 'USD') ? 'US' : 'EU';
	return getTokenCacheDir() . '/' . $accountKey . '_token.cache';
}

function requestNewPayPalAccessToken($currency = 'USD') {
	$clientId = getPayPalClientId($currency);
	$clientSecret = getPayPalClientSecret($currency);

	if (!$clientId || !$clientSecret) {
		logAPICall('token_request_failed', ['reason' => 'Missing credentials', 'currency' => $currency]);
		return false;
	}

	$ch = curl_init('https://api-m.paypal.com/v1/oauth2/token');
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);

	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($httpCode !== 200) {
		logAPICall('token_request_failed', ['http_code' => $httpCode, 'currency' => $currency]);
		return false;
	}

	$tokenData = json_decode($response, true);

	if (!isset($tokenData['access_token']) || !isset($tokenData['expires_in'])) {
		logAPICall('token_request_invalid_response', ['currency' => $currency]);
		return false;
	}

	$cacheData = [
		'access_token' => $tokenData['access_token'],
		'expires_at' => time() + $tokenData['expires_in'] - 60
	];

	$cachePath = getTokenCachePath($currency);
	@file_put_contents($cachePath, json_encode($cacheData));
	@chmod($cachePath, 0600);

	logAPICall('token_refreshed', ['currency' => $currency, 'expires_in' => $tokenData['expires_in']]);

	return $tokenData['access_token'];
}

function getPayPalAccessToken($currency = 'USD') {
	$cachePath = getTokenCachePath($currency);
	$cachedData = @file_get_contents($cachePath);

	if ($cachedData) {
		$tokenData = json_decode($cachedData, true);

		if (isset($tokenData['access_token']) && isset($tokenData['expires_at'])) {
			if (time() < $tokenData['expires_at']) {
				return $tokenData['access_token'];
			}
		}
	}

	return requestNewPayPalAccessToken($currency);
}

function getCachedPayPalAccessToken($currency = 'USD') {
	return getPayPalAccessToken($currency);
}
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

// Derives 'domestic' vs 'world' from the buyer's address, using $shipping_config
// as the source of truth. This is the authoritative check used by patch_order.php —
// it must not be overridable by anything the client sends.
function getShippingMethodByCountry($currency, $countryCode) {
	global $shipping_config;

	$currencyUppercase = strtoupper($currency);
	$countryCodeUppercase = strtoupper($countryCode);

	if (!isset($shipping_config[$currencyUppercase])) {
		return false;
	}

	$countryCodes = $shipping_config[$currencyUppercase]['countryCodes'];
	return in_array($countryCodeUppercase, $countryCodes, true) ? 'domestic' : 'world';
}

// Human-facing id/label for the shipping option PayPal displays, given a currency
// and a 'domestic'/'world' method.
function getShippingOptionMeta($currency, $shippingMethod) {
	$currencyUppercase = strtoupper($currency);

	if ($shippingMethod === 'world') {
		return ['id' => 'SHIP_WORLD', 'label' => 'REST OF THE WORLD'];
	}

	$domesticMeta = [
		'USD' => ['id' => 'SHIP_US', 'label' => 'UNITED STATES'],
		'EUR' => ['id' => 'SHIP_EU', 'label' => 'WITHIN EU'],
		'GBP' => ['id' => 'SHIP_UK', 'label' => 'WITHIN UK']
	];

	return $domesticMeta[$currencyUppercase] ?? ['id' => 'SHIP_DOMESTIC', 'label' => 'DOMESTIC'];
}

// Computes the total shipping fee from $shipping_config for a given currency and
// shipping method ('domestic' or 'world'). If a product isn't sold for that
// shipping method (e.g. subscription-2/-12 aren't in USD's 'world' fees), returns
// an 'error' key instead of silently charging 0 for it.
function getTotalShippingFeeByConfig($items, $currency, $shippingMethod) {
	global $shipping_config;

	$currencyUppercase = strtoupper($currency);
	if (!isset($shipping_config[$currencyUppercase]['fees'][$shippingMethod])) {
		return ['error' => 'Invalid shipping configuration'];
	}

	$fees = $shipping_config[$currencyUppercase]['fees'][$shippingMethod];
	$itemsByType = [];

	foreach ($items as $item) {
		$itemQuantity = intval($item['quantity']);
		$itemType = $item['type'] ?? 'issue';

		if (!isset($fees[$itemType])) {
			return [
				'error' => 'One or more items in your cart are not available for ' .
					($shippingMethod === 'world' ? 'international' : 'domestic') . ' shipping',
				'unavailableType' => $itemType
			];
		}

		$itemsByType[$itemType] = ($itemsByType[$itemType] ?? 0) + $itemQuantity;
	}

	$output = 0;
	foreach ($itemsByType as $type => $quantity) {
		$output += getFeeByAmount($fees[$type], $quantity);
	}

	return ['shippingAmount' => round($output, 2)];
}

// Maps a PayPal shipping option id back to the 'domestic'/'world' method
// getTotalShippingFeeByConfig() expects. Used both as the fallback in
// patch_order.php (no address yet) and to re-derive the method server-side
// in create_order.php, so the mapping only lives in one place.
function getShippingMethodFromOptionId($shippingOptionId) {
	return ($shippingOptionId === 'SHIP_WORLD') ? 'world' : 'domestic';
}

// Computes every shipping option (id, label, amount) for a currency/cart,
// using $shipping_config as the sole source of the fee numbers. Subscription
// items aren't offered international shipping, so 'world' is omitted when
// $hasSubscription is true. Used by create_order.php so the browser never
// has to know a fee number, or even the option id/label shape, itself.
function getAllShippingOptions($currency, $cartItems, $hasSubscription = false) {
	$currencyUppercase = strtoupper($currency);
	$methods = $hasSubscription ? ['domestic'] : ['domestic', 'world'];

	$options = [];
	foreach ($methods as $i => $method) {
		$shippingResult = getTotalShippingFeeByConfig($cartItems, $currencyUppercase, $method);
		if (isset($shippingResult['error'])) {
			return $shippingResult;
		}

		$meta = getShippingOptionMeta($currencyUppercase, $method);
		$options[] = [
			'id' => $meta['id'],
			'label' => $meta['label'],
			'type' => 'SHIPPING',
			'selected' => $i === 0,
			'amount' => [
				'currency_code' => $currencyUppercase,
				'value' => number_format($shippingResult['shippingAmount'], 2, '.', '')
			]
		];
	}

	return ['options' => $options];
}

// Single source of truth for shipping fees. Both create_order.php and
// patch_order.php read from this config through the functions below —
// the browser never computes or is told a fee number. Do not duplicate
// these numbers anywhere else.
$shipping_config = [
	'USD' => [
		'currency' => 'USD',
		'countryCodes' => ['US'],
		'fees' => [
			'domestic' => [
				'issue' => 7.00,
				'annual' => 12.00,
				'archive' => 0,
				'edition' => 45.00,
				'subscription-2' => 14.00,
				'subscription-12' => 84.00
			],
			'world' => [
				'issue' => 15.00,
				'annual' => 30.00,
				'archive' => 0,
				'edition' => 70.00
			]
		]
	],
	'EUR' => [
		'currency' => 'EUR',
		// EU-27 member states (ISO 3166-1 alpha-2). Pending confirmation from
		// the EU manager — see country-code-list for the full-name version sent
		// for review.
		'countryCodes' => [
			'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR',
			'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL',
			'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE'
		],
		'fees' => [
			'domestic' => [
				'issue' => 6.00,
				'annual' => 12.00,
				'archive' => 0,
				'edition' => 45.00,
				'subscription-2' => 12.00,
				'subscription-12' => 72.00
			],
			'world' => [
				'issue' => 12.00,
				'annual' => 25.00,
				'archive' => 0,
				'edition' => 65.00
			]
		]
	],
	'GBP' => [
		'currency' => 'GBP',
		'countryCodes' => ['GB'],
		'fees' => [
			'domestic' => [
				'issue' => 5.00,
				'annual' => 12.00,
				'archive' => 0,
				'edition' => 40.00,
				'subscription-2' => 10.00,
				'subscription-12' => 60.00
			],
			'world' => [
				'issue' => 10.00,
				'annual' => 20.00,
				'archive' => 0,
				'edition' => 55.00
			]
		]
	]
];
