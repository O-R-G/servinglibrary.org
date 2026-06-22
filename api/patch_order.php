<?php
require_once('./api_config.php');

header('Content-Type: application/json');

// Security checks
enforceHTTPS();
setCORSHeaders();
validateContentType();
checkRateLimit();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['error' => 'Method not allowed']);
	exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !is_array($data)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid JSON']);
	exit;
}

$orderId = $data['orderId'] ?? null;
$currency = $data['currency'] ?? 'USD';
$items = $data['items'] ?? [];
$shippingOptionId = $data['shippingOptionId'] ?? null;
$shippingOptions = $data['shippingOptions'] ?? [];
$baseAmount = $data['baseAmount'] ?? 0;
$reference_id = $data['reference_id'] ?? 'default';

// Validate order ID
if (!$orderId || !is_string($orderId)) {
	http_response_code(400);
	echo json_encode(['error' => 'Order ID required']);
	exit;
}

// Validate shipping option ID
if (!$shippingOptionId || !is_string($shippingOptionId)) {
	http_response_code(400);
	echo json_encode(['error' => 'Shipping option ID required']);
	exit;
}

// Validate items
if (empty($items) || !is_array($items)) {
	http_response_code(400);
	echo json_encode(['error' => 'No items provided']);
	exit;
}

// Validate currency
$validCurrencies = ['USD', 'EUR', 'GBP'];
if (!in_array(strtoupper($currency), $validCurrencies)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid currency']);
	exit;
}

// Validate amount
$baseAmount = floatval($baseAmount);
if ($baseAmount < 0) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid base amount']);
	exit;
}

// Validate items format
foreach ($items as $item) {
	if (!isset($item['quantity']) || !isset($item['type'])) {
		http_response_code(400);
		echo json_encode(['error' => 'Invalid item format']);
		exit;
	}

	$quantity = intval($item['quantity']);
	if ($quantity <= 0) {
		http_response_code(400);
		echo json_encode(['error' => 'Invalid quantity']);
		exit;
	}
}

// Validate shipping options
if (!is_array($shippingOptions)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid shipping options format']);
	exit;
}

$currencyUppercase = strtoupper($currency);
$baseAmount = floatval($baseAmount);

// Prepare items for shipping fee calculation
$cartItems = [];
foreach ($items as $item) {
	$cartItems[] = [
		'quantity' => intval($item['quantity']),
		'type' => $item['type'] ?? 'issue'
	];
}

// Calculate new shipping fee
$shippingAmount = getTotalShippingFee($cartItems, $shippingOptionId, $currency);
$totalAmount = $baseAmount + $shippingAmount;

// Update shipping options with correct selection and amounts
foreach ($shippingOptions as &$option) {
	$optionFee = getTotalShippingFee($cartItems, $option['id'], $currency);
	$option['amount']['value'] = number_format($optionFee, 2, '.', '');
	$option['selected'] = ($option['id'] === $shippingOptionId);
}
// Build patch payload for PayPal API
$patchPayload = [
	[
		'op' => 'replace',
		'path' => '/purchase_units/@reference_id==\'' . $reference_id . '\'/amount',
		'value' => [
			'currency_code' => $currencyUppercase,
			'value' => number_format($totalAmount, 2, '.', ''),
			'breakdown' => [
				'item_total' => [
					'currency_code' => $currencyUppercase,
					'value' => number_format($baseAmount, 2, '.', '')
				],
				'shipping' => [
					'currency_code' => $currencyUppercase,
					'value' => number_format($shippingAmount, 2, '.', '')
				]
			]
		]
	],
	[
		'op' => 'replace',
		'path' => '/purchase_units/@reference_id==\'' . $reference_id . '\'/shipping/options',
		'value' => $shippingOptions
	]
];
$access_token = getCachedPayPalAccessToken($currency);

if (!$access_token) {
	http_response_code(500);
	echo json_encode(['error' => 'PayPal credentials not configured']);
	exit;
}

// Call PayPal API
$ch = curl_init('https://api-m.paypal.com/v2/checkout/orders/' . $orderId);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Authorization: Bearer ' . $access_token
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($patchPayload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 204) {
	$responseData = json_decode($response, true);
	http_response_code($httpCode);
	echo json_encode([
		'error' => 'Failed to patch PayPal order',
		'details' => $responseData['details'] ?? $responseData['message'] ?? 'Unknown error'
	]);
	exit;
}

logAPICall('patch_order', [
	'order_id' => $orderId,
	'currency' => $currency,
	'shipping_option' => $shippingOptionId,
	'amount' => $totalAmount,
	'shipping' => $shippingAmount
]);

// Return success
echo json_encode([
	'success' => true,
	'message' => 'Order updated successfully',
	'amount' => number_format($totalAmount, 2, '.', ''),
	'shipping' => number_format($shippingAmount, 2, '.', '')
]);
