<?php
session_start();

// Limpar todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Remover o cookie da sessão, se existir
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000, // Expira o cookie
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// Redirecionar para a página de login
header("Location: login.php");
exit;
?>
