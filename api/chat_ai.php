<?php
require_once '../core/config.php';
require_once '../core/db.php';

header('Content-Type: application/json');

$apiKey = getenv('OPENROUTER_API_KEY');
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['message'] ?? '';

if (!$userMessage) {
    echo json_encode(["reply" => "I'm listening, but I didn't receive a message. How can I help?"]);
    exit;
}

// Fallback logic if API Key is missing or service fails
function getFallbackResponse($msg) {
    $msg = strtolower($msg);
    if (str_contains($msg, 'hi') || str_contains($msg, 'hello')) return "Hello! I'm the Zeoraz AI. I can help you navigate the marketplace, check your analytics, or explain our policies. How are you today?";
    if (str_contains($msg, 'analytics') || str_contains($msg, 'sale')) return "You can view your performance in the Analytics section. Would you like me to walk you through your revenue trends?";
    if (str_contains($msg, 'buy') || str_contains($msg, 'product')) return "Our marketplace features premium goods from verified vendors. You can add items to your cart directly from the home page!";
    if (str_contains($msg, 'who are you')) return "I am the Zeoraz Intelligent Assistant, designed by Shabaab Hanaan to facilitate your high-performance commerce experience.";
    return "That's an interesting question! While my neural links are being optimized, I can tell you that Zeoraz is the future of digital retail. Try asking about 'analytics' or 'marketplace'!";
}

if (!$apiKey || $apiKey === 'your_openrouter_key_here') {
    echo json_encode(["reply" => getFallbackResponse($userMessage)]);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json",
    "HTTP-Referer: http://localhost:8888",
    "X-Title: Zeoraz Marketplace"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful customer support assistant for Zeoraz marketplace. You are professional, concise, and helpful. The site is a premium multi-vendor ecosystem."],
        ["role" => "user", "content" => $userMessage]
    ]
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    echo json_encode(["reply" => getFallbackResponse($userMessage)]);
    exit;
}

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? getFallbackResponse($userMessage);

echo json_encode(["reply" => $reply]);