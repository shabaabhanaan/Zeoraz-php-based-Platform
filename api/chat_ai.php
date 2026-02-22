<?php
require_once '../includes/db.php';

// Simple .env loader
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
    }
}

$apiKey = getenv('OPENROUTER_API_KEY');

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['message'] ?? '';

if (!$userMessage) {
    echo json_encode(["reply" => "Message is empty"]);
    exit;
}


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "openai/gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful customer support assistant for Zeoraz marketplace."],
        ["role" => "user", "content" => $userMessage]
    ]
]));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

$reply = $result['choices'][0]['message']['content'] ?? "Sorry, something went wrong.";

echo json_encode(["reply" => $reply]);