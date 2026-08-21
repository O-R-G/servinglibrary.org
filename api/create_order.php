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

// Input validation
$currency = $data['currency'] ?? 'USD';
$items = $data['items'] ?? [];
$hasSubscription = !empty($data['hasSubscription']);

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

// Validate items format
foreach ($items as $item) {
	if (!isset($item['name']) || !isset($item['unit_amount']) || !isset($item['quantity'])) {
		http_response_code(400);
		echo json_encode(['error' => 'Invalid item format']);
		exit;
	}

	$price = floatval($item['unit_amount']['value']);
	$quantity = intval($item['quantity']);

	if ($price < 0 || $quantity <= 0) {
		http_response_code(400);
		echo json_encode(['error' => 'Invalid price or quantity']);
		exit;
	}
}

$currencyUppercase = strtoupper($currency);

// Calculate totals
$itemTotal = 0;
$itemsForPayPal = [];
$cartItems = []; // For shipping fee calculation

foreach ($items as $item) {
	$unitAmount = (float)$item['unit_amount']['value'];
	$quantity = (int)$item['quantity'];
	$itemTotal += $unitAmount * $quantity;

	$itemsForPayPal[] = [
		'name' => $item['name'],
		'unit_amount' => [
			'currency_code' => $currencyUppercase,
			'value' => number_format($unitAmount, 2, '.', '')
		],
		'quantity' => (string)$quantity
	];

	// Store item info for shipping fee calculation
	$cartItems[] = [
		'quantity' => $quantity,
		'type' => $item['type'] ?? 'issue'
	];
}

// Shipping options (id, label, amount) are computed entirely server-side from
// $shipping_config — the same function patch_order.php's amounts derive from.
// The client only tells us the cart and whether it's a subscription order;
// it never supplies a fee or an option shape.
$shippingResult = getAllShippingOptions($currencyUppercase, $cartItems, $hasSubscription);
if (isset($shippingResult['error'])) {
	http_response_code(400);
	echo json_encode($shippingResult);
	exit;
}
$shippingOptions = $shippingResult['options'];

// Get first shipping option amount (default selection)
$shippingAmount = !empty($shippingOptions) ? (float)$shippingOptions[0]['amount']['value'] : 0;
$totalAmount = $itemTotal + $shippingAmount;

// Build order payload for PayPal API
$orderPayload = [
	'intent' => 'CAPTURE',
	'purchase_units' => [
		[
            'reference_id' => '781012',
			'amount' => [
				'currency_code' => $currencyUppercase,
				'value' => number_format($totalAmount, 2, '.', ''),
				'breakdown' => [
					'item_total' => [
						'currency_code' => $currencyUppercase,
						'value' => number_format($itemTotal, 2, '.', '')
					],
					'shipping' => [
						'currency_code' => $currencyUppercase,
						'value' => number_format($shippingAmount, 2, '.', '')
					]
				]
			],
			'shipping' => [
				'options' => $shippingOptions
			],
			'items' => $itemsForPayPal
		]
	]
];

$access_token = getPayPalAccessToken($currency);

if (!$access_token) {
	http_response_code(500);
	echo json_encode(['error' => 'PayPal credentials not configured']);
	exit;
}

// Call PayPal API
$ch = curl_init('https://api-m.paypal.com/v2/checkout/orders');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json',
	'Authorization: Bearer ' . $access_token
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderPayload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$responseData = json_decode($response, true);

if ($httpCode !== 201) {
	http_response_code($httpCode);
	echo json_encode([
		'error' => 'Failed to create PayPal order',
		'details' => $responseData['details'] ?? $responseData['message'] ?? 'Unknown error'
	]);
	exit;
}

logAPICall('create_order', [
	'order_id' => $responseData['id'],
	'currency' => $currency,
	'amount' => $totalAmount,
	'item_count' => count($items)
]);

// Return the order ID
echo json_encode([
	'id' => $responseData['id'],
	'status' => $responseData['status']
]);
